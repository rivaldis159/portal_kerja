<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\Link;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PortalController
{
    public function index()
    {
        $user = Auth::user();
        if ($user->last_login < now()->startOfDay()) {
            $user->updateLastLogin();
        }

        // Get user's teams with active links
        $teams = $user->teams()
            ->with(['links' => function ($query) {
                $query->where('is_active', true)
                    ->with('category')
                    ->orderBy('order');
            }])
            ->get();

        // Get announcements (global + team-specific)
        $teamIds = $teams->pluck('id');
        $announcements = Announcement::active()
            ->with('team') // <--- TAMBAHKAN INI (Eager Load)
            ->where(function ($query) use ($teamIds) {
                $query->whereNull('team_id')
                    ->orWhereIn('team_id', $teamIds);
            })
            ->orderByRaw("FIELD(priority, 'urgent', 'high', 'normal', 'low')")
            ->take(5)
            ->get();

        // Get most used links for quick access
       $recentLinks = Link::active()
            ->with('category') // <--- TAMBAHKAN INI (Eager Load Category)
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

        // Check if user has access to this link
        if (!$user->teams()->where('teams.id', $link->team_id)->exists()) {
            abort(403, 'Unauthorized access to this link.');
        }

        // Log the access
        $link->logAccess($user);

        return redirect()->away($link->url);
    }

    // Existing login methods...
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

        // Optimasi: Tambahkan with('team', 'category')
        $links = Link::active()
            ->with(['team', 'category']) // <--- Eager Loading (PENTING)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();

        // Optimasi: Tambahkan with('team')
        $announcements = Announcement::active()
            ->with('team') // <--- Eager Loading (PENTING)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                ->orWhere('content', 'like', "%{$query}%");
            })
            ->get();

        return view('portal.search', compact('links', 'announcements', 'query'));
    }
}
