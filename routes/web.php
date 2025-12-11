<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PortalController;


Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('portal.index');
    }
    return view('portal.login');
})->name('login');

Route::post('/login', [PortalController::class, 'loginAction'])->name('login.action');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', function() {
        return redirect()->route('portal.index');
    });

    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');
    
    // Rute Profile (Tampilan & Aksi Update)
    Route::get('/portal/profile', [PortalController::class, 'profile'])->name('portal.profile');
    // Tambahkan baris ini agar form update profil berfungsi:
    Route::put('/portal/profile', [PortalController::class, 'updateProfile'])->name('portal.profile.update');
    
    Route::get('/link/{link}', [PortalController::class, 'redirect'])->name('link.redirect');
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
