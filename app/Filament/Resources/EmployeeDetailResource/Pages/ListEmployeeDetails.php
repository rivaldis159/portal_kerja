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

    // Tim Subbag Umum (Kita asumsikan role khusus atau Admin Tim Umum) & Super Admin bisa lihat semua
    // Anggap ID Tim Subbag Umum adalah 1 (nanti sesuaikan ID-nya)
    if ($user->isSuperAdmin() || ($user->team_id == 1 && $user->isAdminTim())) {
         return parent::getTableQuery();
    }

    // Pegawai biasa hanya lihat datanya sendiri
    return parent::getTableQuery()->where('user_id', $user->id);
}
}
