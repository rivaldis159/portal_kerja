<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class PortalController extends Controller
{
    // 1. Halaman Utama (Dashboard Pegawai)
    public function index()
    {
        $user = auth()->user();
        
        // Ambil kategori beserta link-nya
        $categories = Category::where('is_active', true)
            ->with(['links' => function($query) use ($user) {
                $query->where('is_active', true)
                      ->where(function($q) use ($user) {
                          // A. Link Pusat (Semua bisa lihat)
                          $q->where('is_bps_pusat', true);
                          
                          if ($user) {
                              // B. Link Tim Sendiri
                              $q->orWhere('team_id', $user->team_id);
                              // C. Link Tim Lain yang PUBLIK
                              $q->orWhere('is_public', true);
                          } else {
                              // D. Jika Tamu (belum login), hanya lihat yang publik
                              $q->orWhere('is_public', true);
                          }
                      });
            }])
            ->get();

        return view('portal.index', compact('categories'));
    }

    // 2. Tampilkan Form Login
    public function loginForm()
    {
        // Jika sudah login, lempar ke halaman utama
        if (Auth::check()) {
            return redirect('/');
        }
        return view('portal.login');
    }

    // 3. Proses Login (Ini yang menyebabkan error sebelumnya)
    public function loginAction(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect ke halaman yang dituju atau ke home
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 4. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}