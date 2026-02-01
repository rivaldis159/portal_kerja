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
        // 1. Data Tabel Pegawai (dengan Pencarian)
        $query = \App\Models\User::query()
            ->with(['employeeDetail', 'teams'])
            ->where('role', '!=', 'super_admin'); // Sembunyikan akun super admin

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('employeeDetail', function ($sub) use ($search) {
                        $sub->where('nip', 'like', "%{$search}%")
                            ->orWhere('jabatan', 'like', "%{$search}%");
                    });
            });
        }

        $employees = $query->paginate(10);

        // 2. Data Statistik untuk Grafik

        // A. Grafik Jabatan (Pie Chart)
        $jabatanStats = \App\Models\EmployeeDetail::select('jabatan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('jabatan')
            ->groupBy('jabatan')
            ->pluck('total', 'jabatan')
            ->toArray();

        // B. Grafik Pangkat/Golongan (Bar Chart)
        $golonganStats = \App\Models\EmployeeDetail::select('pangkat_golongan', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
            ->whereNotNull('pangkat_golongan')
            ->orderBy('pangkat_golongan')
            ->groupBy('pangkat_golongan')
            ->pluck('total', 'pangkat_golongan')
            ->toArray();

        // C. Total Data Ringkas
        $totalPegawai = \App\Models\User::where('role', '!=', 'super_admin')->count();
        $totalTim = \App\Models\Team::count();

        return view('portal.stats', compact(
            'employees',
            'jabatanStats',
            'golonganStats',
            'totalPegawai',
            'totalTim'
        ));
    }
}
