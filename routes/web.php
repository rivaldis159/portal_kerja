<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});
// Portal routes
Route::get('/login', [PortalController::class, 'login'])->name('login');
Route::post('/login', [PortalController::class, 'doLogin']);
Route::post('/logout', [PortalController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/portal', [PortalController::class, 'index'])->name('portal');
    Route::get('/portal/search', [PortalController::class, 'search'])->name('portal.search');
    Route::get('/link/{link}', [PortalController::class, 'redirectToLink'])->name('link.redirect');
});

// API route untuk search suggestions
Route::get('/api/search-links', function() {
    $query = request('q');
    $user = Auth::user();

    if (!$user || strlen($query) < 2) {
        return response()->json([]);
    }

    $teamIds = $user->teams->pluck('id');

    $links = \App\Models\Link::active()
        ->whereIn('team_id', $teamIds)
        ->where(function($q) use ($query) {
            $q->where('title', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })
        ->with(['team', 'category'])
        ->limit(10)
        ->get()
        ->map(function($link) {
            return [
                'id' => $link->id,
                'title' => $link->title,
                'description' => $link->description,
                'color' => $link->color,
                'team_name' => $link->team->name,
                'category_name' => optional($link->category)->name,
            ];
        });

    return response()->json($links);
})->middleware('auth');
