<?php

namespace App\Filament\Resources\AccessLogResource\Pages;

use App\Filament\Resources\AccessLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAccessLogs extends ManageRecords
{
    protected static string $resource = AccessLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\AccessLogResource\Widgets\AccessLogStats::class,
        ];
    }
}
