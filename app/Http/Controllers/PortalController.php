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
    // 1. Halaman Utama
    public function index()
    {
        $user = auth()->user();
        $teams = Team::all();

        $categories = Category::where('is_active', true)
            ->with(['links' => function($query) use ($user) {
                $query->where('is_active', true)
                      ->where(function($q) use ($user) {
                          $q->where('is_bps_pusat', true);
                          if ($user) {
                              $q->orWhere('team_id', $user->team_id);
                              $q->orWhere('is_public', true);
                          } else {
                              $q->orWhere('is_public', true);
                          }
                      });
            }])
            ->get();

        $announcements = collect(); 
        if ($user) {
            $announcements = Announcement::where('is_active', true)
                ->where('team_id', $user->team_id)
                ->latest()
                ->get();
        }

        return view('portal.index', compact('categories', 'announcements', 'teams'));
    }

    // 2. Login Form
    public function loginForm()
    {
        if (Auth::check()) return redirect('/');
        return view('portal.login');
    }

    // 3. Proses Login
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

    // 4. Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // 5. Tampilkan Profil
    public function profile()
    {
        $user = Auth::user();
        // Load data detail pegawai jika ada
        $user->load('employeeDetail');
        return view('portal.profile', compact('user'));
    }

    // 6. Simpan Profil (Update Lengkap)
public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            // User Login Data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            
            // Employee Details
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

        // 1. Update User Utama
        $user->name = $validated['name'];
        $user->email = $validated['email']; // Update email login
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // 2. Update Detail Pegawai
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
                // Kita gunakan email user utama sebagai email kantor juga
                'email_kantor' => $validated['email'], 
            ]
        );

        return back()->with('success', 'Data pegawai berhasil diperbarui.');
    }
}