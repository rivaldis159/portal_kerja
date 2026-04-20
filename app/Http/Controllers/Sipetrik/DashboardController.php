<?php

namespace App\Http\Controllers\Sipetrik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mitra' => \App\Models\Mitra::count(),
            'total_contracts' => \App\Models\PenawaranKerja::count(),
            'active_contracts' => \App\Models\PenawaranKerja::whereIn('status', ['offered', 'accepted'])->count(),
            'completed_contracts' => \App\Models\PenawaranKerja::where('status', 'completed')->count(),
            'total_value' => \App\Models\PenawaranKerja::sum('nilai_kontrak'),
        ];

        $recent_reviews = \App\Models\PenilaianKinerja::with(['contract.mitra', 'contract.team'])->latest()->take(5)->get();

        return view('sipetrik.dashboard', compact('stats', 'recent_reviews'));
    }
}
