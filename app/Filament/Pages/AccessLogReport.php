<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\AccessLog;
use App\Models\Link;
use Illuminate\Support\Facades\DB;

class AccessLogReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Reports';
    protected static string $view = 'filament.pages.access-log-report';

    public function getViewData(): array
    {
        $topLinks = Link::withCount('accessLogs')
            ->orderBy('access_logs_count', 'desc')
            ->take(10)
            ->get();

        $dailyAccess = AccessLog::select(
                DB::raw('DATE(accessed_at) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->where('accessed_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'topLinks' => $topLinks,
            'dailyAccess' => $dailyAccess,
        ];
    }
}
