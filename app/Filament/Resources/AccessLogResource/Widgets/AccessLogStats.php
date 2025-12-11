<?php

namespace App\Filament\Resources\AccessLogResource\Widgets;

use App\Models\AccessLog;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class AccessLogStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Akses Hari Ini', AccessLog::whereDate('created_at', Carbon::today())->count())
                ->description('Jumlah klik hari ini')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Akses Bulan Ini', AccessLog::whereMonth('created_at', Carbon::now()->month)->count())
                ->description('Total klik bulan ' . Carbon::now()->format('F'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('info'),

            Stat::make('Total Semua Akses', AccessLog::count())
                ->description('Akumulasi sejak awal')
                ->chart([7, 2, 10, 3, 15, 4, 17]) // Dummy chart untuk visual
                ->color('primary'),
        ];
    }
}