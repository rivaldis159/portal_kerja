<?php

namespace App\Filament\Resources\TeamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static ?string $title = 'Anggota Tim';
    protected static ?string $modelLabel = 'Anggota';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name') // Pencarian berdasarkan nama user
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),
                
                // Menampilkan Peran (Admin/Member)
                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Peran di Tim Ini')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'warning', // Kuning untuk Admin
                        'member' => 'info',   // Biru untuk Anggota
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin Tim',
                        'member' => 'Anggota Biasa',
                        default => $state,
                    }),
            ])
            ->headerActions([
                // Tombol "Tambah Anggota" (Pilih dari user yg sudah ada)
                Tables\Actions\AttachAction::make()
                    ->label('Tambah Anggota')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->label('Pilih Pegawai')
                            ->searchable()
                            ->required(),
                        
                        // Dropdown Peran saat menambahkan
                        Forms\Components\Select::make('role')
                            ->label('Peran')
                            ->options([
                                'member' => 'Anggota Biasa',
                                'admin' => 'Admin Tim',
                            ])
                            ->required()
                            ->default('member')
                            ->helperText('Admin Tim bisa mengelola link milik tim ini.'),
                    ]),
            ])
            ->actions([
                // Edit Peran (Misal naik pangkat jadi Admin Tim)
                Tables\Actions\EditAction::make()
                    ->label('Ubah Peran')
                    ->form([
                        Forms\Components\Select::make('role')
                            ->label('Peran')
                            ->options([
                                'member' => 'Anggota Biasa',
                                'admin' => 'Admin Tim',
                            ])
                            ->required(),
                    ]),
                
                // Hapus dari Tim
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan'),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }
}