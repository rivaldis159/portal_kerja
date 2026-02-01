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
        
        // Validasi input
        $validated = $request->validate([
            // User Data
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            
            // Employee Detail Data (Boleh kosong / nullable)
            'nip' => 'nullable|string|max:18',
            'nik' => 'nullable|string|max:16',
            'pangkat_golongan' => 'nullable|string',
            'jabatan' => 'nullable|string',
            'masa_kerja' => 'nullable|string',
            'pendidikan_terakhir' => 'nullable|string',
            'tempat_lahir' => 'nullable|string',
            'tanggal_lahir' => 'nullable|date',
            'alamat_tinggal' => 'nullable|string',
            'status_perkawinan' => 'nullable|string',
            'nama_pasangan' => 'nullable|string',
            'nomor_rekening' => 'nullable|string',
            'bank_name' => 'nullable|string',
            'email_kantor' => 'nullable|email',
        ]);

        // 1. Simpan Data Akun Utama
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        // 2. Simpan Data Detail Pegawai
        // Kita gunakan updateOrCreate agar jika data belum ada, akan dibuatkan baru
        $user->employeeDetail()->updateOrCreate(
            ['user_id' => $user->id], // Kriteria
            [
                'nip' => $request->nip,
                'nik' => $request->nik,
                'pangkat_golongan' => $request->pangkat_golongan,
                'jabatan' => $request->jabatan,
                'masa_kerja' => $request->masa_kerja,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat_tinggal' => $request->alamat_tinggal,
                'status_perkawinan' => $request->status_perkawinan,
                'nama_pasangan' => $request->nama_pasangan,
                'nomor_rekening' => $request->nomor_rekening,
                'bank_name' => $request->bank_name,
                'email_kantor' => $request->email_kantor,
            ]
        );

        return back()->with('success', 'Data profil lengkap berhasil diperbarui.');
    }
}