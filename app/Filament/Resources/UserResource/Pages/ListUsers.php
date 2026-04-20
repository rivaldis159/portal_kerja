<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Imports\ImportPegawai;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\FileUpload;
use Illuminate\Http\Request;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('importPegawai')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->visible(fn () => auth()->user()->isSuperAdmin() || auth()->user()->isKepala())
                ->form([
                    FileUpload::make('attachment')
                        ->label('Upload File Excel (.xlsx)')
                        ->disk('public')
                        ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $filePath = storage_path('app/public/' . $data['attachment']);

                    Excel::import(new ImportPegawai, $filePath);

                    \Filament\Notifications\Notification::make()
                        ->title('Data Pegawai Berhasil Diimport')
                        ->success()
                        ->send();
                }),
        ];
    }
}
