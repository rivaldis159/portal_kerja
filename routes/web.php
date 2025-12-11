<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PortalController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [PortalController::class, 'login'])->name('login');
Route::post('/login', [PortalController::class, 'doLogin']);
Route::post('/logout', [PortalController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/portal', [PortalController::class, 'index'])->name('portal');
    Route::get('/portal/search', [PortalController::class, 'search'])->name('portal.search');
    Route::get('/link/{link}', [PortalController::class, 'redirectToLink'])->name('link.redirect');
    Route::get('/profile', [PortalController::class, 'editProfile'])->name('portal.profile');
    Route::put('/profile', [PortalController::class, 'updateProfile'])->name('portal.profile.update');
});


Route::get('/api/search-links', function (\Illuminate\Http\Request $request) {
    $query = $request->get('q');
    
    if (!$query) return response()->json([]);

    $links = \App\Models\Link::query()
        ->visibleToUser(Auth::user()) // PENTING: Gunakan scope yang kita buat di Step 2
        ->where('is_active', true)
        ->where('title', 'like', "%{$query}%")
        ->with(['team', 'category'])
        ->limit(10)
        ->get()
        ->map(function ($link) {
            return [
                'id' => $link->id,
                'title' => $link->title,
                'url' => $link->url,
                'color' => $link->color,
                'team_name' => $link->team->name,
                'category_name' => $link->category->name ?? 'Lainnya',
            ];
        });

    return response()->json($links);
})->middleware('auth');
