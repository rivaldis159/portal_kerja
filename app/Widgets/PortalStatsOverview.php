<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Team;
use App\Models\Link;
use App\Models\AccessLog;

class PortalStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Active: ' . User::where('is_active', true)->count())
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Teams', Team::count())
                ->description('Avg users per team: ' . round(User::count() / max(Team::count(), 1), 1))
                ->descriptionIcon('heroicon-m-user-group'),

            Stat::make('Active Links', Link::where('is_active', true)->count())
                ->description('Total clicks today: ' . AccessLog::whereDate('accessed_at', today())->count())
                ->descriptionIcon('heroicon-m-link')
                ->color('warning'),

            Stat::make('Daily Active Users', User::where('last_login', '>=', now()->startOfDay())->count())
                ->description('Last 7 days: ' . User::where('last_login', '>=', now()->subDays(7))->count())
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
        ];
    }
}
