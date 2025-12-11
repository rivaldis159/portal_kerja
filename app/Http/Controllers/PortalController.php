<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Link;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil tim beserta link yang HANYA boleh dilihat oleh user (Publik / Tim Sendiri)
        $teams = Team::whereHas('links', function ($query) use ($user) {
            $query->visibleToUser($user)->where('is_active', true);
        })
        ->with(['links' => function ($query) use ($user) {
            $query->visibleToUser($user)
                ->where('is_active', true)
                ->orderBy('order')
                ->with('category');
        }])
        ->orderBy('name')
        ->get();

        $announcements = Announcement::latest()->take(5)->get();

        return view('portal.index', compact('teams', 'announcements'));
    }

    public function redirectToLink($linkId)
    {
        $user = Auth::user();
        
        // Cek permission menggunakan Scope visibleToUser agar konsisten
        // Jika link tidak publik DAN user bukan timnya, akan otomatis 404/Fail
        $link = Link::visibleToUser($user)->findOrFail($linkId);

        $link->logAccess($user);

        return redirect()->away($link->url);
    }

    public function login()
    {
        // Jika sudah login, lempar ke portal (Preventif tambahan)
        if (Auth::check()) {
            return redirect()->route('portal.index');
        }
        return view('portal.login');
    }

    // PERBAIKAN: Mengganti nama method dari 'doLogin' menjadi 'loginAction' sesuai route
    public function loginAction() 
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate(); // Security practice
            return redirect()->intended('/portal');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $user = Auth::user();

        // PERBAIKAN: Menambahkan filter visibleToUser agar link privat tidak bocor di pencarian
        $links = Link::visibleToUser($user)
            ->active()
            ->with(['team', 'category'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            })
            ->take(20) // Limit hasil agar tidak berat
            ->get();

        // Filter pengumuman (Opsional: bisa ditambahkan logic tim_id juga jika perlu)
        $announcements = Announcement::query() // Pastikan model Announcement ada scopeActive atau hapus ->active() jika belum ada
            ->with('team')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%");
            })
            ->get();

        return view('portal.search', compact('links', 'announcements', 'query'));
    }

    // PERBAIKAN: Mengganti nama method 'editProfile' menjadi 'profile' sesuai route
    public function profile()
    {
        return view('portal.profile', [
            'user' => Auth::user()
        ]);
    }

    // Pastikan Anda juga menambahkan Route::post('/portal/profile', ...) untuk method ini di web.php jika belum
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}