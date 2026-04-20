<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PortalController;


Route::get('/', [PortalController::class, 'landing'])->name('landing');

Route::get('/login', function () {
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
    Route::get('/home', function () {
        return redirect()->route('portal.index');
    });

    Route::get('/portal', [PortalController::class, 'index'])->name('portal.index');

    Route::get('/portal/profile', [PortalController::class, 'profile'])->name('portal.profile');
    Route::put('/portal/profile', [PortalController::class, 'updateProfile'])->name('portal.profile.update');

    Route::get('/statistik-pegawai', [PortalController::class, 'stats'])->name('portal.stats');
    Route::get('/portal/employees/search', [PortalController::class, 'searchEmployees'])->name('portal.employees.search');
});

// Public link redirect (accessible from PODA landing page without login)
Route::get('/link/{link}', [PortalController::class, 'redirect'])->name('link.redirect');


Route::get('/api/search-links', [PortalController::class, 'searchLinks'])->middleware('auth')->name('api.links.search');

Route::middleware(['auth', 'super_admin'])->prefix('sipetrik')->name('sipetrik.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Sipetrik\DashboardController::class, 'index'])->name('dashboard');
    Route::post('mitras/import', [\App\Http\Controllers\Sipetrik\MitraController::class, 'import'])->name('mitras.import');
    Route::resource('mitras', \App\Http\Controllers\Sipetrik\MitraController::class);
    Route::patch('penawaran-kerja/{id}/status', [\App\Http\Controllers\Sipetrik\PenawaranKerjaController::class, 'updateStatus'])->name('penawaran-kerja.update-status');
    Route::resource('penawaran-kerja', \App\Http\Controllers\Sipetrik\PenawaranKerjaController::class);
    Route::resource('penilaian', \App\Http\Controllers\Sipetrik\PenilaianController::class);
});


