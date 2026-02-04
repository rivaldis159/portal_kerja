<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Team;

class PortalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Ambil ID & Nama Tim untuk filter dropdown (jika perlu)
        $teams = Team::select('id', 'name')->get();

        $categories = Category::where('is_active', true)
            ->with(['links' => function ($query) use ($user) {
                $query->where('is_active', true);

                // Super Admin & Kepala bisa melihat semua link aktif
                if ($user && ($user->isSuperAdmin() || $user->isKepala())) {
                    return;
                }

                $query->where(function ($q) use ($user) {
                        $q->where('is_public', true)
                          ->orWhere('is_bps_pusat', true);
                        
                        if ($user) {
                            $myTeamIds = $user->teams->pluck('id')->toArray();
                            if (!empty($myTeamIds)) {
                                $q->orWhereIn('team_id', $myTeamIds);
                            }
                        }
                    });
            }])
            ->orderBy('name', 'asc')
            ->get();

        // Filter duplikasi URL di level Collection (PHP) dengan Prioritas
        $categories->each(function ($category) use ($user) {
            $myTeamIds = $user ? $user->teams->pluck('id')->toArray() : [];

            $uniqueLinks = $category->links
                ->sortByDesc(function ($link) use ($myTeamIds) {
                    $score = 0;
                    if ($link->is_bps_pusat) $score += 100;
                    if (in_array($link->team_id, $myTeamIds)) $score += 50;
                    return $score + $link->updated_at->timestamp;
                })
                ->unique('url') // Ambil satu saja berdasarkan URL (item dengan sorting teratas yang diambil)
                ->values(); // Reset index array

            $category->setRelation('links', $uniqueLinks);
        });

        return view('portal.index', compact('categories', 'teams'));
    }

    public function loginForm()
    {
        if (Auth::check()) return redirect('/');
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
            return redirect()->intended('/');
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
            'email' => 'required|email|unique:users,email,' . $user->id,
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
        if (!empty($validated['password'])) {
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
                
                'masa_kerja_tahun' => (function() use ($request) {
                    if ($request->nip && strlen($request->nip) == 18) {
                        try {
                            $isPPPK = str_contains($request->pangkat_golongan, 'PPPK');
                            
                            if ($isPPPK) {
                                $yearString = substr($request->nip, 8, 4);
                                $startYear = (int)$yearString;
                                $currentYear = (int)date('Y');
                                return max(0, $currentYear - $startYear);
                            } else {
                                $tmtString = substr($request->nip, 8, 6);
                                $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);
                                return $tmtDate->diff(\Carbon\Carbon::now())->y;
                            }
                        } catch (\Exception $e) { return 0; }
                    }
                    return 0; 
                })(),
                
                'masa_kerja_bulan' => (function() use ($request) {
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
                        } catch (\Exception $e) { return 0; }
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
        // --- LOGIKA UTAMA TABEL & SORTING ---
        $query = \App\Models\User::query()
            ->with(['employeeDetail', 'teams'])
            ->where('role', '!=', 'super_admin');

        // Filter Pencarian
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

        // JOIN table detail agar bisa sort kolom jabatan/nip
        $query->select('users.*')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id');

        if ($sortColumn === 'name') {
            $query->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'jabatan') {
            $pangkatOrder = $this->getPangkatOrder();
            $sqlCase = "CASE employee_details.pangkat_golongan ";
            foreach ($pangkatOrder as $index => $pangkat) {
                $sqlCase .= "WHEN '$pangkat' THEN ".($index + 1)." ";
            }
            $sqlCase .= "ELSE 999 END"; 
            $query->orderByRaw("$sqlCase $sortDirection");
        } elseif ($sortColumn === 'masa_kerja') {
            $query->orderByRaw("SUBSTR(employee_details.nip, 9, 6) " . $sortDirection);
        }

        $employees = $query->paginate(20)->withQueryString();

        // --- BAGIAN STATISTIK GRAFIK (OPTIMIZED) ---
        // Gunakan DB::raw untuk menghitung count langsung di database, bukan di PHP
        $jabatanStats = \App\Models\EmployeeDetail::select('jabatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('jabatan')
            ->pluck('total', 'jabatan')
            ->toArray();
        
        $pangkatOrder = $this->getPangkatOrder();
        $golonganStatsRaw = \App\Models\EmployeeDetail::select('pangkat_golongan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('pangkat_golongan')
            ->pluck('total', 'pangkat_golongan')
            ->toArray();
            
        // Sorting golongan tetap di PHP karena jumlahnya sedikit (hasil group by)
        $golonganStats = collect($golonganStatsRaw)->sortBy(function ($count, $key) use ($pangkatOrder) {
            return array_search($key, $pangkatOrder) !== false ? array_search($key, $pangkatOrder) : 999;
        })->toArray();

        $pendidikanStatsRaw = \App\Models\EmployeeDetail::select('pendidikan_strata', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->groupBy('pendidikan_strata')
            ->pluck('total', 'pendidikan_strata')
            ->toArray();

        $pendidikanOrder = ['SMA/SMK', 'D-I', 'D-II', 'D-III', 'D-IV', 'S-1', 'S-2', 'S-3'];

        $pendidikanStats = collect($pendidikanStatsRaw)->sortBy(function ($count, $key) use ($pendidikanOrder) {
            return array_search($key, $pendidikanOrder) !== false ? array_search($key, $pendidikanOrder) : 999;
        })->toArray();

        // Untuk Umur & Masa Kerja, kita ambil data partial saja untuk dihitung di PHP loop
        // Ini jauh lebih ringan daripada mengambil semua kolom (seperti foto, alamat dsb)
        $partialDetails = \App\Models\EmployeeDetail::select('nip', 'tanggal_lahir', 'pangkat_golongan')->get();

        $umurStats = ['< 30 Thn' => 0, '30-40 Thn' => 0, '41-50 Thn' => 0, '> 50 Thn' => 0];
        $masaKerjaStats = ['< 5 Thn' => 0, '5-10 Thn' => 0, '10-20 Thn' => 0, '> 20 Thn' => 0];

        foreach ($partialDetails as $d) {
            // Hitung Umur
            if ($d->tanggal_lahir) {
                // Optimasi: pake diffInYears Carbon langsung
                $age = \Carbon\Carbon::parse($d->tanggal_lahir)->age;
                if ($age < 30) $umurStats['< 30 Thn']++;
                elseif ($age <= 40) $umurStats['30-40 Thn']++;
                elseif ($age <= 50) $umurStats['41-50 Thn']++;
                else $umurStats['> 50 Thn']++;
            }

            // Hitung Masa Kerja
            if ($d->nip && strlen($d->nip) == 18) {
                try {
                    $isPPPK = str_contains($d->pangkat_golongan, 'PPPK');
                    $years = 0;

                    if ($isPPPK) {
                         $startYear = (int)substr($d->nip, 8, 4);
                         $years = max(0, (int)date('Y') - $startYear);
                    } else {
                        $tmtString = substr($d->nip, 8, 6);
                        $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);
                        $years = $tmtDate->diffInYears(now());
                    }

                    if ($years < 5) $masaKerjaStats['< 5 Thn']++;
                    elseif ($years <= 10) $masaKerjaStats['5-10 Thn']++;
                    elseif ($years <= 20) $masaKerjaStats['10-20 Thn']++;
                    else $masaKerjaStats['> 20 Thn']++;
                } catch (\Exception $e) { }
            }
        }

        $totalPegawai = \App\Models\User::where('role', '!=', 'super_admin')->count();
        
        $avgAge = \App\Models\EmployeeDetail::whereNotNull('tanggal_lahir')
            ->selectRaw('AVG(DATEDIFF(NOW(), tanggal_lahir) / 365.25) as avg_age')
            ->value('avg_age');
            
        $avgAge = round($avgAge ?: 0);

        // Options for Filters
        $filterJabatan = \App\Models\EmployeeDetail::distinct()->whereNotNull('jabatan')->pluck('jabatan')->sort()->values();
        $filterPangkat = \App\Models\EmployeeDetail::distinct()->whereNotNull('pangkat_golongan')->pluck('pangkat_golongan')->sort()->values();

        return view('portal.stats', compact(
            'employees', 'jabatanStats', 'golonganStats', 'pendidikanStats', 'umurStats', 'masaKerjaStats', 'totalPegawai', 'avgAge',
            'filterJabatan', 'filterPangkat'
        ));
    }

    public function searchEmployees(Request $request)
    {
        $query = \App\Models\User::query()
            ->with(['employeeDetail', 'teams'])
            ->where('role', '!=', 'super_admin');

        // Apply Filters
        if ($request->filled('jabatan')) {
            $query->whereHas('employeeDetail', fn($q) => $q->where('jabatan', $request->jabatan));
        }
        if ($request->filled('pangkat')) {
            $query->whereHas('employeeDetail', fn($q) => $q->where('pangkat_golongan', $request->pangkat));
        }
        if ($request->filled('pendidikan')) {
            $query->whereHas('employeeDetail', fn($q) => $q->where('pendidikan_strata', $request->pendidikan));
        }
        if ($request->filled('masa_kerja')) {
            $query->whereHas('employeeDetail', function($q) use ($request) {
                // Assuming 'masa_kerja_tahun' is stored in DB.
                // Filter ranges based on the char logic: <5, 5-10, 10-20, >20
                switch ($request->masa_kerja) {
                    case 'lt5': $q->where('masa_kerja_tahun', '<', 5); break;
                    case '5-10': $q->whereBetween('masa_kerja_tahun', [5, 10]); break;
                    case '10-20': $q->whereBetween('masa_kerja_tahun', [10, 20]); break;
                    case 'gt20': $q->where('masa_kerja_tahun', '>', 20); break;
                }
            });
        }

        // Filter Pencarian (Keyword)
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
             $pangkatOrder = $this->getPangkatOrder();
             
            $sqlCase = "CASE employee_details.pangkat_golongan ";
            foreach ($pangkatOrder as $index => $pangkat) {
                $sqlCase .= "WHEN '$pangkat' THEN ".($index + 1)." ";
            }
            $sqlCase .= "ELSE 999 END";
            
            $rankParams = $sortDirection === 'asc' ? 'desc' : 'asc';
            
            $query->orderByRaw("$sqlCase $rankParams")
                  ->orderBy('users.name', 'asc');
        } elseif ($sortColumn === 'masa_kerja') {
            // Sort using database column 'masa_kerja_tahun' is more efficient/correct if available
            // but for consistency with 'stats' calculation we used substring before.
            // If masa_kerja_tahun is reliably populated:
             $query->orderBy('employee_details.masa_kerja_tahun', $sortDirection);
        }

        if ($perPage == 'all') {
            $employees = $query->get();
        } else {
            $employees = $query->paginate((int)$perPage)->withQueryString();
        }

        return view('portal.partials.employee-table', compact('employees'))->render();
    }

    public function redirect(\App\Models\Link $link)
    {
        // Catat Log Akses
        \App\Models\AccessLog::create([
            'user_id' => \Illuminate\Support\Facades\Auth::id(),
            'link_id' => $link->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'accessed_at' => now(),
        ]);

        // Redirect ke URL asli
        return redirect()->away($link->url);
    }

    private function getPangkatOrder()
    {
        return [
            'Pembina Utama (IV/e)', 
            'PPPK Ahli Utama (XV)',
            'Pembina Utama Madya (IV/d)', 'Pembina Utama Muda (IV/c)', 'Pembina Tingkat I (IV/b)', 'Pembina (IV/a)', 'Pembina (V/a)',
            'PPPK Ahli Madya (XIII)',
            'Penata Tingkat I (III/d)', 'Penata (III/c)', 
            'PPPK Ahli Muda (XI)',
            'Penata Muda Tingkat I (III/b)', 'Penata Muda (III/a)',
            'PPPK Ahli Pertama (IX)',
            'Pengatur Tingkat I (II/d)', 'Pengatur (II/c)', 
            'PPPK Terampil (VII)',
            'Pengatur Muda Tingkat I (II/b)', 'Pengatur Muda (II/a)',
            'Juru Tingkat I (I/d)', 'Juru (I/c)', 
            'PPPK Penata Layanan Operasional (V)', 'PPPK Pengelola Umum (V)',
            'Juru Muda Tingkat I (I/b)', 'Juru Muda (I/a)'
        ];
    }
}
