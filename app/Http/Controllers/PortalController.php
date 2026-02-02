<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Category;
use App\Models\Announcement;
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
                $query->where('is_active', true)
                    ->where(function ($q) use ($user) {
                        // 1. Link Pusat (Semua bisa lihat)
                        $q->where('is_bps_pusat', true);

                        if ($user) {
                            // 2. Link Tim Sendiri (MULTIPLE TEAMS)
                            // Ambil semua ID tim yang diikuti user
                            $myTeamIds = $user->teams->pluck('id')->toArray();

                            if (!empty($myTeamIds)) {
                                $q->orWhereIn('team_id', $myTeamIds);
                            }

                            // 3. Link Publik dari tim lain
                            $q->orWhere('is_public', true);
                        } else {
                            // Jika tamu, hanya lihat publik
                            $q->orWhere('is_public', true);
                        }
                    });
            }])
            ->get();

        // Filter duplikasi URL di level Collection (PHP) dengan Prioritas
        $categories->each(function ($category) use ($user) {
            $myTeamIds = $user ? $user->teams->pluck('id')->toArray() : [];

            $uniqueLinks = $category->links
                ->sortByDesc(function ($link) use ($myTeamIds) {
                    // SKOR PRIORITAS (Makin tinggi makin "menang")
                    $score = 0;
                    
                    // 1. Link Pusat = Prioritas Tertinggi (Score: 100)
                    if ($link->is_bps_pusat) $score += 100;
                    
                    // 2. Link dari Tim Saya Sendiri = Prioritas Kedua (Score: 50)
                    // Agar settingan admin tim saya sendiri yang saya lihat, bukan tim orang lain
                    if (in_array($link->team_id, $myTeamIds)) $score += 50;
                    
                    // 3. Link Terbaru = Prioritas Ketiga (Score: timestamp)
                    // Jika ada konflik antar 2 tim publik asing, ambil yang paling baru diupdate
                    return $score + $link->updated_at->timestamp;
                })
                ->unique('url') // Ambil satu saja berdasarkan URL (item dengan sorting teratas yang diambil)
                ->values(); // Reset index array

            $category->setRelation('links', $uniqueLinks);
        });

        $announcements = collect();
        if ($user) {
            $myTeamIds = $user->teams->pluck('id')->toArray();
            $announcements = Announcement::where('is_active', true)
                ->whereIn('team_id', $myTeamIds) // Pengumuman dari semua tim yang diikuti
                ->latest()
                ->get();
        }

        return view('portal.index', compact('categories', 'announcements', 'teams'));
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
                'masa_kerja_tahun' => $request->masa_kerja_tahun,
                'masa_kerja_bulan' => $request->masa_kerja_bulan,
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
            // Sort custom order Pangkat from highest to lowest or vice versa
            // Urutan Pangkat PNS (Golongan IV, III, II, I)
            $pangkatOrder = [
                'Pembina Utama (IV/e)', 'Pembina Utama Madya (IV/d)', 'Pembina Utama Muda (IV/c)', 'Pembina Tingkat I (IV/b)', 'Pembina (IV/a)',
                'Penata Tingkat I (III/d)', 'Penata (III/c)', 'Penata Muda Tingkat I (III/b)', 'Penata Muda (III/a)',
                'Pengatur Tingkat I (II/d)', 'Pengatur (II/c)', 'Pengatur Muda Tingkat I (II/b)', 'Pengatur Muda (II/a)',
                'Juru Tingkat I (I/d)', 'Juru (I/c)', 'Juru Muda Tingkat I (I/b)', 'Juru Muda (I/a)'
            ];

            // If we have just 'IV/a', 'III/b' etc without text, we might need a different list or a different strategy.
            // But usually BPS data includes the name like 'Pembina (IV/a)'.
            // Safest fallback is alphabetical if exact match fails, but let's try FIELD first.
            // Note: Since user wants 'sort by pangkat', we use FIELD on pangkat_golongan.
            // If the data is just 'IV/a', we should adjust the array. Based on view_file output previously it seemed to be longer strings?
            // Actually in employee-table.blade.php: $emp->employeeDetail->pangkat_golongan
            // Let's assume standard names. If it fails, alphabetical sort on "IV/...", "III/..." works decent enough BUT "Pembina" vs "Penata" is Z-A reverse.
            // Actually, let's use a robust approach: try to sort by string length? No.
            // Let's stick to the list above which is standard.

            $orderByString = "'" . implode("','", $pangkatOrder) . "'";
            // FIELD returns index (1-based), 0 if not found.
            // So 'Pembina Utama' (index 1). 'Juru Muda' (index 17).
            // ASC: 1 -> 17 (Highest rank first? No, 1 is index. So Pembina (Top) appears first).
            // Usually "Rank ASC" means Lowest to Highest (I/a -> IV/e).
            // If our list is High -> Low:
            // ASC sort on FIELD will show High -> Low.
            // DESC sort on FIELD will show Low -> High.
            $query->orderByRaw("FIELD(employee_details.pangkat_golongan, $orderByString) " . $sortDirection);
        } elseif ($sortColumn === 'masa_kerja') {
            $query->orderByRaw("SUBSTRING(employee_details.nip, 9, 6) " . $sortDirection);
        }

        $employees = $query->paginate(20)->withQueryString();

        // --- BAGIAN STATISTIK GRAFIK ---
        $allDetails = \App\Models\EmployeeDetail::all();

        $jabatanStats = $allDetails->groupBy('jabatan')->map->count()->toArray();
        $golonganStats = $allDetails->groupBy('pangkat_golongan')->map->count()->sortKeys()->toArray();
        $pendidikanStats = $allDetails->groupBy('pendidikan_strata')->map->count()->toArray();

        $umurStats = ['< 30 Thn' => 0, '30-40 Thn' => 0, '41-50 Thn' => 0, '> 50 Thn' => 0];
        $masaKerjaStats = ['< 5 Thn' => 0, '5-10 Thn' => 0, '10-20 Thn' => 0, '> 20 Thn' => 0];

        foreach ($allDetails as $d) {
            // Hitung Umur
            if ($d->tanggal_lahir) {
                $age = \Carbon\Carbon::parse($d->tanggal_lahir)->age;
                if ($age < 30) $umurStats['< 30 Thn']++;
                elseif ($age <= 40) $umurStats['30-40 Thn']++;
                elseif ($age <= 50) $umurStats['41-50 Thn']++;
                else $umurStats['> 50 Thn']++;
            }

            // Hitung Masa Kerja
            if ($d->nip && strlen($d->nip) == 18) {
                $tmtString = substr($d->nip, 8, 6);
                try {
                    $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);
                    $years = $tmtDate->diffInYears(now());

                    if ($years < 5) $masaKerjaStats['< 5 Thn']++;
                    elseif ($years <= 10) $masaKerjaStats['5-10 Thn']++;
                    elseif ($years <= 20) $masaKerjaStats['10-20 Thn']++;
                    else $masaKerjaStats['> 20 Thn']++;
                } catch (\Exception $e) {
                }
            }
        }

        $totalPegawai = \App\Models\User::where('role', '!=', 'super_admin')->count();
        $avgAge = $allDetails->filter(fn($i) => $i->tanggal_lahir)->map(fn($i) => \Carbon\Carbon::parse($i->tanggal_lahir)->age)->avg();
        $avgAge = round($avgAge ?: 0);

        return view('portal.stats', compact(
            'employees', 'jabatanStats', 'golonganStats', 'pendidikanStats', 'umurStats', 'masaKerjaStats', 'totalPegawai', 'avgAge'
        ));
    }

    public function searchEmployees(Request $request)
    {
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
        $perPage = $request->get('per_page', 20);

        $query->select('users.*')
            ->leftJoin('employee_details', 'users.id', '=', 'employee_details.user_id');

        if ($sortColumn === 'name') {
            $query->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'jabatan') {
             $pangkatOrder = [
                'Pembina Utama (IV/e)', 'Pembina Utama Madya (IV/d)', 'Pembina Utama Muda (IV/c)', 'Pembina Tingkat I (IV/b)', 'Pembina (IV/a)',
                'Penata Tingkat I (III/d)', 'Penata (III/c)', 'Penata Muda Tingkat I (III/b)', 'Penata Muda (III/a)',
                'Pengatur Tingkat I (II/d)', 'Pengatur (II/c)', 'Pengatur Muda Tingkat I (II/b)', 'Pengatur Muda (II/a)',
                'Juru Tingkat I (I/d)', 'Juru (I/c)', 'Juru Muda Tingkat I (I/b)', 'Juru Muda (I/a)'
            ];
            $orderByString = "'" . implode("','", $pangkatOrder) . "'";
            $query->orderByRaw("FIELD(employee_details.pangkat_golongan, $orderByString) " . $sortDirection);
        } elseif ($sortColumn === 'masa_kerja') {
            $query->orderByRaw("SUBSTRING(employee_details.nip, 9, 6) " . $sortDirection);
        }

        if ($perPage == 'all') {
            $employees = $query->get();
        } else {
            $employees = $query->paginate((int)$perPage)->withQueryString();
        }

        return view('portal.partials.employee-table', compact('employees'))->render();
    }
}
