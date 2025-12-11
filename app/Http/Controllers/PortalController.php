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
        $user = Auth::user();
        if ($user->last_login < now()->startOfDay()) {
            $user->updateLastLogin();
        }

        $teams = $user->teams()
            ->with(['links' => function ($query) {
                $query->where('is_active', true)
                    ->with('category')
                    ->orderBy('order');
            }])
            ->get();

        $teamIds = $teams->pluck('id');
        $announcements = Announcement::active()
            ->with('team')
            ->where(function ($query) use ($teamIds) {
                $query->whereNull('team_id')
                    ->orWhereIn('team_id', $teamIds);
            })
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->take(5)
            ->get();

       $recentLinks = Link::active()
            ->with('category')
            ->whereIn('team_id', $teamIds)
            ->withCount(['accessLogs as recent_clicks' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('accessed_at', '>=', now()->subDays(7));
            }])
            ->orderBy('recent_clicks', 'desc')
            ->take(8)
            ->get();

        return view('portal.index', compact('teams', 'announcements', 'recentLinks'));
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
