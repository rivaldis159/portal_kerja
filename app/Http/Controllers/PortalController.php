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
            // 1. Sort Pegawai (Abjad Nama)
            $query->orderBy('users.name', $sortDirection);
        } elseif ($sortColumn === 'jabatan') {
            // 2. Sort Jabatan (CUSTOM ORDER SESUAI PERMINTAAN)
            $jabatanOrder = [
                'Kepala BPS Kabupaten Dairi',
                'Kepala Subbagian Umum',
                'Penata Laksana Barang Terampil',
                'Analis Pengelola Keuangan APBN Ahli Muda',
                'Pranata Keuangan APBN Mahir',
                'Analis Anggaran Ahli Muda',
                'Analis Anggaran Ahli Pertama',
                'Pustakawan Mahir',
                'Pustakawan Terampil',
                'Pengelola Barang Milik Negara',
                'Pengolah Data',
                'Pranata Kearsipan',
                'Statistisi Ahli Madya',
                'Statistisi Ahli Muda',
                'Statistisi Ahli Pertama',
                'Statistisi Mahir',
                'Statistisi Terampil',
                'Pranata Komputer Ahli Madya',
                'Pranata Komputer Ahli Muda',
                'Pranata Komputer Ahli Pertama',
                'Pranata Komputer Penyelia',
                'Pranata Komputer Mahir',
                'Pranata Komputer Terampil',
                'Pengolah Data'
            ];

            // Ubah array jadi string untuk query SQL
            $orderByString = "'" . implode("','", $jabatanOrder) . "'";

            // Field yang tidak ada di list akan ditaruh di urutan terakhir
            $query->orderByRaw("FIELD(employee_details.jabatan, $orderByString) " . $sortDirection);
        } elseif ($sortColumn === 'masa_kerja') {
            // 3. Sort Masa Kerja (Berdasarkan TMT di dalam NIP)
            // Kita ambil digit ke-9 sebanyak 6 karakter (YYYYMM TMT)
            // Logic:
            // - Jika ASC (Lama): Kita cari TMT tahun 'kecil' (misal 1998) -> ASC
            // - Jika DESC (Baru): Kita cari TMT tahun 'besar' (misal 2024) -> DESC

            $query->orderByRaw("SUBSTRING(employee_details.nip, 9, 6) " . $sortDirection);
        }

        $employees = $query->paginate(20)->withQueryString();

        // --- BAGIAN STATISTIK GRAFIK (TETAP SAMA) ---
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

        $birthdayUsers = \App\Models\User::whereHas('employeeDetail', function ($q) {
            $q->whereMonth('tanggal_lahir', now()->month);
        })->with('employeeDetail')->get();

        $totalPegawai = \App\Models\User::where('role', '!=', 'super_admin')->count();
        $avgAge = $allDetails->filter(fn($i) => $i->tanggal_lahir)->map(fn($i) => \Carbon\Carbon::parse($i->tanggal_lahir)->age)->avg();
        $avgAge = round($avgAge ?: 0);

        return view('portal.stats', compact(
            'employees',
            'jabatanStats',
            'golonganStats',
            'pendidikanStats',
            'umurStats',
            'masaKerjaStats',
            'birthdayUsers',
            'totalPegawai',
            'avgAge'
        ));
    }
}
