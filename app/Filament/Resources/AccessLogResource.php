<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AccessLogResource\Pages;
use App\Models\AccessLog;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AccessLogResource extends Resource
{
    protected static ?string $model = AccessLog::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Laporan Akses';
    protected static ?string $pluralModelLabel = 'Log Akses';
    protected static ?int $navigationSort = 100;

    // Matikan fitur tambah/edit/hapus (Hanya Laporan)
    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }
    public static function canDelete(Model $record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Ganti 'created_at' menjadi 'accessed_at'
                Tables\Columns\TextColumn::make('accessed_at') 
                    ->label('Waktu Akses')
                    ->dateTime('d M Y, H:i:s')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('link.title')
                    ->label('Link Diakses')
                    ->searchable()
                    ->sortable()
                    ->description(fn (AccessLog $record) => $record->link?->team?->name ?? '-'),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->placeholder('Guest / Belum Login'),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('user_agent')
                    ->label('Perangkat/Browser')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->user_agent),
            ])
            // Ganti default sort ke 'accessed_at'
            ->defaultSort('accessed_at', 'desc') 
            ->filters([
                Tables\Filters\Filter::make('accessed_at') // Ubah nama filter agar konsisten (opsional tapi rapi)
                    ->form([
                        Forms\Components\DatePicker::make('created_from')->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('created_until')->label('Sampai Tanggal'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            // Ganti kolom query menjadi 'accessed_at'
                            ->when($data['created_from'], fn ($q) => $q->whereDate('accessed_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('accessed_at', '<=', $data['created_until']));
                    })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAccessLogs::route('/'),
        ];
    }
}