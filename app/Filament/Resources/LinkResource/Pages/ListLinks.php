<?php

namespace App\Filament\Resources\LinkResource\Pages;

use App\Filament\Resources\LinkResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLinks extends ListRecords
{
    protected static string $resource = LinkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $user = auth()->user();

        // Super Admin & Kepala lihat semua
        if ($user->isSuperAdmin() || $user->isKepala()) {
            return parent::getTableQuery();
        }

        // Admin Tim lihat: Link timnya SENDIRI atau Link PUSAT
        return parent::getTableQuery()->where(function($query) use ($user) {
            $query->where('team_id', $user->team_id)
                  ->orWhere('is_bps_pusat', true);
        });
    }
}