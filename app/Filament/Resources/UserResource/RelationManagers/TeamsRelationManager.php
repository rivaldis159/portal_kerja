<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TeamsRelationManager extends RelationManager
{
    protected static string $relationship = 'teams';
    protected static ?string $title = 'Keanggotaan Tim';
    protected static ?string $modelLabel = 'Tim';

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
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tim')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('pivot.role')
                    ->label('Status di Tim')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'warning',
                        'member' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'admin' => 'Admin Tim',
                        'member' => 'Anggota Biasa',
                        default => $state,
                    }),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->label('Masukkan ke Tim')
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        
                        Forms\Components\Select::make('role')
                            ->label('Peran di Tim Ini')
                            ->options([
                                'member' => 'Anggota Biasa',
                                'admin' => 'Admin Tim',
                            ])
                            ->required()
                            ->default('member'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah Peran')
                    ->form([
                        Forms\Components\Select::make('role')
                            ->label('Peran di Tim Ini')
                            ->options([
                                'member' => 'Anggota Biasa',
                                'admin' => 'Admin Tim',
                            ])
                            ->required(),
                    ]),
                    
                Tables\Actions\DetachAction::make()
                    ->label('Keluarkan'),
            ]);
    }
}