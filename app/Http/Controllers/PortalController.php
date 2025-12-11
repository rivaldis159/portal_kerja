<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Link;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PortalController
{
    public function index()
    {
        // 1. Ambil data Team, Link, dan Kategori sekaligus (Eager Loading)
        $teams = Team::with(['links' => function($query) {
            $query->orderBy('category_id')->orderBy('title');
        }, 'links.category'])->get();

        // 2. Ambil 5 pengumuman terbaru
        $announcements = Announcement::latest()->where('is_active', true)->take(5)->get();

        // 3. Kirim ke view
        return view('portal.index', compact('teams', 'announcements'));
    }

    public function redirectToLink($linkId)
    {
        $link = Link::findOrFail($linkId);
        $user = Auth::user();

        if (!$user->teams()->where('teams.id', $link->team_id)->exists()) {
            abort(403, 'Unauthorized access to this link.');
        }

        $link->logAccess($user);

        return redirect()->away($link->url);
    }

    public function login()
    {
        return view('portal.login');
    }

    public function doLogin()
    {
        $credentials = request()->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            return redirect('/portal');
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        $links = Link::active()
            ->with(['team', 'category'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();

        $announcements = Announcement::active()
            ->with('team')
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%");
            })
            ->get();

        return view('portal.search', compact('links', 'announcements', 'query'));
    }

    public function editProfile()
    {
        return view('portal.profile', [
            'user' => Auth::user()
        ]);
    }

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
