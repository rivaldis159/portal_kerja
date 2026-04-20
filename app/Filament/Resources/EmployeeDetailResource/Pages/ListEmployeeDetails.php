<?php

namespace App\Filament\Resources\EmployeeDetailResource\Pages;

use App\Filament\Resources\EmployeeDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEmployeeDetails extends ListRecords
{
    protected static string $resource = EmployeeDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?\Illuminate\Database\Eloquent\Builder
    {
        $user = auth()->user();

        if ($user->isSuperAdmin() || ($user->team_id == 1 && $user->isAdminTim())) {
            return parent::getTableQuery();
        }

        return parent::getTableQuery()->where('user_id', $user->id);
    }
}
