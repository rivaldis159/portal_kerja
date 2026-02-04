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
        if ($user->isSuperAdmin() || $user->isKepala()) {
            return parent::getTableQuery();
        }
        $adminTeamIds = $user->teams()
            ->wherePivot('role', 'admin')
            ->pluck('teams.id')
            ->toArray();
        return parent::getTableQuery()->where(function ($query) use ($user, $adminTeamIds) {
            $query->whereIn('team_id', $adminTeamIds)
                ->orWhere('is_bps_pusat', true);
        });
    }
}
