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

        // 1. Super Admin / Kepala: Lihat Semua
        if ($user->isSuperAdmin() || $user->isKepala()) {
            return parent::getTableQuery();
        }

        // 2. Pegawai Biasa:
        // Logic Baru: Tampilkan Link milik tim dimana user ini menjadi ADMIN
        // Ambil ID tim dimana user punya role 'admin'
        $adminTeamIds = $user->teams()
            ->wherePivot('role', 'admin')
            ->pluck('teams.id') // Ambil ID timnya
            ->toArray();

        return parent::getTableQuery()->where(function ($query) use ($user, $adminTeamIds) {
            // Tampilkan link jika tim_id nya ada di daftar tim yang dia kuasai
            $query->whereIn('team_id', $adminTeamIds)
                ->orWhere('is_bps_pusat', true);
        });
    }
}
