<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PortalController extends Controller
{
    /**
     * Public landing page — PODA
     * Shows all public links in a flat grid (no category grouping)
     */
    public function landing()
    {
        $links = Link::where('is_active', true)
            ->where('is_public', true)
            ->with(['team', 'category'])
            ->orderByDesc('is_bps_pusat')
            ->orderBy('title', 'asc')
            ->get()
            ->unique('url')
            ->values();

        return view('portal.landing', compact('links'));
    }

    /**
     * Internal portal — requires auth
     * Shows links grouped by category → subcategory
     */
    public function index()
    {
        $user = auth()->user();

        $categories = Category::where('is_active', true)
            ->with(['subcategories' => function ($query) {
                $query->where('is_active', true)->ordered();
            }, 'subcategories.links' => function ($query) use ($user) {
                $query->where('is_active', true);

                if ($user && ($user->isSuperAdmin() || $user->isKepala())) {
                    return;
                }

                $query->where(function ($q) use ($user) {
                    $q->where('is_public', true)
                        ->orWhere('is_bps_pusat', true);

                    if ($user) {
                        $myTeamIds = $user->teams->pluck('id')->toArray();
                        if (! empty($myTeamIds)) {
                            $q->orWhereIn('team_id', $myTeamIds);
                        }
                    }
                });
            }])
            ->ordered()
            ->get();

        // Also load links directly on category (for backward compat with links that have no subcategory)
        $categories->load(['links' => function ($query) use ($user) {
            $query->where('is_active', true)->whereNull('subcategory_id');

            if ($user && ($user->isSuperAdmin() || $user->isKepala())) {
                return;
            }

            $query->where(function ($q) use ($user) {
                $q->where('is_public', true)
                    ->orWhere('is_bps_pusat', true);

                if ($user) {
                    $myTeamIds = $user->teams->pluck('id')->toArray();
                    if (! empty($myTeamIds)) {
                        $q->orWhereIn('team_id', $myTeamIds);
                    }
                }
            });
        }]);

        // Deduplicate and sort links within each subcategory
        $myTeamIds = $user ? $user->teams->pluck('id')->toArray() : [];
        $categories->each(function ($category) use ($myTeamIds) {
            $category->subcategories->each(function ($subcategory) use ($myTeamIds) {
                $uniqueLinks = $subcategory->links
                    ->sortByDesc(function ($link) use ($myTeamIds) {
                        $score = 0;
                        if ($link->is_bps_pusat) $score += 100;
                        if (in_array($link->team_id, $myTeamIds)) $score += 50;
                        return $score + $link->updated_at->timestamp;
                    })
                    ->unique('url')
                    ->values();
                $subcategory->setRelation('links', $uniqueLinks);
            });
        });

        return view('portal.index', compact('categories'));
    }

    public function loginForm()
    {
        if (Auth::check()) {
            return redirect('/');
        }

        return view('portal.login');
    }

    public function loginAction(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/portal');
        }

        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function profile()
    {
        $user = Auth::user();
        $user->load('employeeDetail');

        return view('portal.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'nullable|min:8|confirmed',
            'nip' => 'nullable|numeric|digits:18',
            'nip_lama' => 'nullable|numeric|digits:9',
            'pangkat_golongan' => 'nullable|string',
            'tmt_pangkat' => 'nullable|date',
            'masa_kerja_tahun' => 'nullable|integer',
            'masa_kerja_bulan' => 'nullable|integer',
            'jabatan' => 'nullable|string',
            'pendidikan_strata' => 'nullable|string',
            'pendidikan_jurusan' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'nik' => 'nullable|numeric',
            'alamat_tinggal' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'nama_pasangan' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        $user->employeeDetail()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nip' => $request->nip,
                'nip_lama' => $request->nip_lama,
                'pangkat_golongan' => $request->pangkat_golongan,
                'tmt_pangkat' => $request->tmt_pangkat,

                'masa_kerja_tahun' => (function () use ($request) {
                    if ($request->nip && strlen($request->nip) == 18) {
                        try {
                            $isPPPK = str_contains($request->pangkat_golongan, 'PPPK');

                            if ($isPPPK) {
                                $yearString = substr($request->nip, 8, 4);
                                $startYear = (int) $yearString;
                                $currentYear = (int) date('Y');

                                return max(0, $currentYear - $startYear);
                            } else {
                                $tmtString = substr($request->nip, 8, 6);
                                $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);

                                return $tmtDate->diff(\Carbon\Carbon::now())->y;
                            }
                        } catch (\Exception $e) {
                            return 0;
                        }
                    }

                    return 0;
                })(),

                'masa_kerja_bulan' => (function () use ($request) {
                    if ($request->nip && strlen($request->nip) == 18) {
                        try {
                            $isPPPK = str_contains($request->pangkat_golongan, 'PPPK');

                            if ($isPPPK) {
                                return 0;
                            } else {
                                $tmtString = substr($request->nip, 8, 6);
                                $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);

                                return $tmtDate->diff(\Carbon\Carbon::now())->m;
                            }
                        } catch (\Exception $e) {
                            return 0;
                        }
                    }

                    return 0;
                })(),

                'jabatan' => $request->jabatan,
                'pendidikan_strata' => $request->pendidikan_strata,
                'pendidikan_jurusan' => $request->pendidikan_jurusan,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'nik' => $request->nik,
                'alamat_tinggal' => $request->alamat_tinggal,
                'status_perkawinan' => $request->status_perkawinan,
                'nama_pasangan' => $request->nama_pasangan,
                'bank_name' => $request->bank_name,
                'nomor_rekening' => $request->nomor_rekening,
                'email_kantor' => $validated['email'],
            ]
        );

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function stats(Request $request)
    {
        $statsData = cache()->remember('portal_stats_data', 300, function () {
            $jabatanStats = \App\Models\EmployeeDetail::select('jabatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->whereNotNull('jabatan')
                ->groupBy('jabatan')
                ->pluck('total', 'jabatan')
                ->toArray();

            $pangkatOrder = \App\Support\EmployeeConfig::getPangkatOrder();
            $golonganStatsRaw = \App\Models\EmployeeDetail::select('pangkat_golongan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->whereNotNull('pangkat_golongan')
                ->groupBy('pangkat_golongan')
                ->pluck('total', 'pangkat_golongan')
                ->toArray();

            $golonganStats = collect($golonganStatsRaw)->sortBy(function ($count, $key) use ($pangkatOrder) {
                return array_search($key, $pangkatOrder) !== false ? array_search($key, $pangkatOrder) : 999;
            })->toArray();

            $pendidikanOrder = \App\Support\EmployeeConfig::getPendidikanOrder();
            $pendidikanStatsRaw = \App\Models\EmployeeDetail::select('pendidikan_strata', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->whereNotNull('pendidikan_strata')
                ->groupBy('pendidikan_strata')
                ->pluck('total', 'pendidikan_strata')
                ->toArray();

            $pendidikanStats = collect($pendidikanStatsRaw)->sortBy(function ($count, $key) use ($pendidikanOrder) {
                return array_search($key, $pendidikanOrder) !== false ? array_search($key, $pendidikanOrder) : 999;
            })->toArray();

            $umurStatsQuery = \App\Models\EmployeeDetail::select(\Illuminate\Support\Facades\DB::raw("
                CASE 
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) < 30 THEN '< 30 Thn'
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 30 AND 40 THEN '30-40 Thn'
                    WHEN TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE()) BETWEEN 41 AND 50 THEN '41-50 Thn'
                    ELSE '> 50 Thn'
                END as age_group,
                count(*) as total
            "))
            ->whereNotNull('tanggal_lahir')
            ->groupBy('age_group')
            ->pluck('total', 'age_group')
            ->toArray();

            $umurStats = array_merge(
                ['< 30 Thn' => 0, '30-40 Thn' => 0, '41-50 Thn' => 0, '> 50 Thn' => 0],
                $umurStatsQuery
            );

            $currentYear = date('Y');
            $masaKerjaStatsQuery = \App\Models\EmployeeDetail::select(\Illuminate\Support\Facades\DB::raw("
                CASE 
                    WHEN ($currentYear - CONVERT(SUBSTRING(nip, 9, 4), UNSIGNED)) < 5 THEN '< 5 Thn'
                    WHEN ($currentYear - CONVERT(SUBSTRING(nip, 9, 4), UNSIGNED)) BETWEEN 5 AND 10 THEN '5-10 Thn'
                    WHEN ($currentYear - CONVERT(SUBSTRING(nip, 9, 4), UNSIGNED)) BETWEEN 11 AND 20 THEN '10-20 Thn'
                    ELSE '> 20 Thn'
                END as tenure_group,
                count(*) as total
            "))
            ->whereRaw('LENGTH(nip) = 18')
            ->groupBy('tenure_group')
            ->pluck('total', 'tenure_group')
            ->toArray();

            $masaKerjaStats = array_merge(
                ['< 5 Thn' => 0, '5-10 Thn' => 0, '10-20 Thn' => 0, '> 20 Thn' => 0],
                $masaKerjaStatsQuery
            );

            $totalPegawai = \App\Models\User::where('role', '!=', 'super_admin')->count();

            $avgAge = \App\Models\EmployeeDetail::whereNotNull('tanggal_lahir')
                ->selectRaw('AVG(TIMESTAMPDIFF(YEAR, tanggal_lahir, CURDATE())) as avg_age')
                ->value('avg_age');
            $avgAge = round($avgAge ?: 0);

            $filterJabatan = collect(array_keys($jabatanStats))->sort()->values();

            $filterPangkat = collect(array_keys($golonganStats))->values();

            $filterPendidikan = collect(array_keys($pendidikanStats))->values();

            return compact(
                'jabatanStats', 'golonganStats', 'pendidikanStats', 'umurStats', 'masaKerjaStats',
                'totalPegawai', 'avgAge', 'filterJabatan', 'filterPangkat', 'filterPendidikan'
            );
        });

        $query = \App\Models\User::query()
            ->with(['employeeDetail', 'teams'])
            ->where('role', '!=', 'super_admin');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('employeeDetail', function ($sub) use ($search) {
                        $sub->where('nip', 'like', "%{$search}%")
                            ->orWhere('nip_lama', 'like', "%{$search}%")
                            ->orWhere('jabatan', 'like', "%{$search}%");
                    });
            });
        }

        $sortColumn = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');

        $query->select('users.*')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id');

        if ($sortColumn === 'name') {
            $query->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'jabatan') {
            $pangkatOrder = \App\Support\EmployeeConfig::getPangkatOrder();
            $sqlCase = 'CASE employee_details.pangkat_golongan ';
            foreach ($pangkatOrder as $index => $pangkat) {
                $sqlCase .= "WHEN '$pangkat' THEN ".($index + 1).' ';
            }
            $sqlCase .= 'ELSE 999 END';
            $query->orderByRaw("$sqlCase $sortDirection");
        } elseif ($sortColumn === 'masa_kerja') {
            $query->orderByRaw("CASE WHEN LENGTH(employee_details.nip) = 18 THEN 1 ELSE 0 END DESC");

            $sqlTenure = "
                CASE 
                    WHEN employee_details.pangkat_golongan LIKE '%PPPK%' THEN 
                        (YEAR(CURDATE()) - CAST(SUBSTR(employee_details.nip, 9, 4) AS UNSIGNED)) * 12
                    ELSE 
                        TIMESTAMPDIFF(MONTH, STR_TO_DATE(CONCAT(SUBSTR(employee_details.nip, 9, 6), '01'), '%Y%m%d'), CURDATE())
                END
            ";
            
            $query->orderByRaw("$sqlTenure $sortDirection");
        }

        $employees = $query->paginate(20)->withQueryString();

        return view('portal.stats', array_merge(
            $statsData,
            compact('employees')
        ));
    }

    public function searchEmployees(Request $request)
    {
        $query = \App\Models\User::query()
            ->with(['employeeDetail', 'teams'])
            ->where('role', '!=', 'super_admin');


        if ($request->filled('jabatan')) {
            $query->whereHas('employeeDetail', fn ($q) => $q->where('jabatan', $request->jabatan));
        }
        if ($request->filled('pangkat')) {
            $query->whereHas('employeeDetail', fn ($q) => $q->where('pangkat_golongan', $request->pangkat));
        }
        if ($request->filled('pendidikan')) {
            $query->whereHas('employeeDetail', fn ($q) => $q->where('pendidikan_strata', $request->pendidikan));
        }
        if ($request->filled('masa_kerja')) {
            $query->whereHas('employeeDetail', function ($q) use ($request) {

                
                $yearExpression = "
                    CASE 
                        WHEN pangkat_golongan LIKE '%PPPK%' THEN (YEAR(CURDATE()) - CAST(SUBSTR(nip, 9, 4) AS UNSIGNED))
                        ELSE TIMESTAMPDIFF(YEAR, STR_TO_DATE(CONCAT(SUBSTR(nip, 9, 6), '01'), '%Y%m%d'), CURDATE())
                    END
                ";

                switch ($request->masa_kerja) {
                    case 'lt5': 
                        $q->whereRaw("$yearExpression < 5");
                        break;
                    case '5-10': 
                        $q->whereRaw("$yearExpression BETWEEN 5 AND 10");
                        break;
                    case '10-20': 
                        $q->whereRaw("$yearExpression BETWEEN 10 AND 20");
                        break;
                    case 'gt20': 
                        $q->whereRaw("$yearExpression > 20");
                        break;
                }
            });
        }


        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('employeeDetail', function ($sub) use ($search) {
                        $sub->where('nip', 'like', "%{$search}%")
                            ->orWhere('nip_lama', 'like', "%{$search}%")
                            ->orWhere('jabatan', 'like', "%{$search}%");
                    });
            });
        }

        $sortColumn = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        $perPage = $request->get('per_page', 20);

        $query->select('users.*')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id');

        if ($sortColumn === 'name') {
            $query->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'jabatan') {
            $pangkatOrder = \App\Support\EmployeeConfig::getPangkatOrder();

            $sqlCase = 'CASE employee_details.pangkat_golongan ';
            foreach ($pangkatOrder as $index => $pangkat) {
                $sqlCase .= "WHEN '$pangkat' THEN ".($index + 1).' ';
            }
            $sqlCase .= 'ELSE 999 END';

            $rankParams = $sortDirection === 'asc' ? 'desc' : 'asc';

            $query->orderByRaw("$sqlCase $rankParams")
                ->orderBy('users.name', 'asc');
        } elseif ($sortColumn === 'masa_kerja') {

            $query->orderByRaw("CASE WHEN LENGTH(employee_details.nip) = 18 THEN 1 ELSE 0 END DESC");

            $sqlTenure = "
                CASE 
                    WHEN employee_details.pangkat_golongan LIKE '%PPPK%' THEN 
                        (YEAR(CURDATE()) - CAST(SUBSTR(employee_details.nip, 9, 4) AS UNSIGNED)) * 12
                    ELSE 
                        TIMESTAMPDIFF(MONTH, STR_TO_DATE(CONCAT(SUBSTR(employee_details.nip, 9, 6), '01'), '%Y%m%d'), CURDATE())
                END
            ";
            
            $query->orderByRaw("$sqlTenure $sortDirection");
        }

        if ($perPage == 'all') {
            $employees = $query->get();
        } else {
            $employees = $query->paginate((int) $perPage)->withQueryString();
        }

        return view('portal.partials.employee-table', compact('employees'))->render();
    }


    public function redirect(\App\Models\Link $link)
    {
        \App\Models\AccessLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'link_id' => $link->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'accessed_at' => now(),
        ]);

        return redirect()->away($link->url);
    }

    public function searchLinks(Request $request)
    {
        $query = $request->get('q');
    
        if (!$query) return response()->json([]);
    
        $links = \App\Models\Link::query()
            ->visibleToUser(\Illuminate\Support\Facades\Auth::user())
            ->where('is_active', true)
            ->where('title', 'like', "%{$query}%")
            ->with(['team', 'category'])
            ->limit(10)
            ->get()
            ->map(function ($link) {
                return [
                    'id' => $link->id,
                    'title' => $link->title,
                    'url' => $link->url,
                    'color' => $link->color,
                    'team_name' => $link->team->name,
                    'category_name' => $link->category->name ?? 'Lainnya',
                ];
            });
    
        return response()->json($links);
    }


}
