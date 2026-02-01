<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\TeamResource\RelationManagers;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?string $navigationLabel = 'Data Tim';
    protected static ?string $modelLabel = 'Tim';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Tim')
                    ->required()
                    ->maxLength(255),

                // WARNA OTOMATIS & UNIK
                Forms\Components\ColorPicker::make('color')
                    ->label('Warna Identitas')
                    ->required()
                    ->default(function () {
                        // Loop sampai nemu warna yang belum dipakai
                        do {
                            $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
                        } while (Team::where('color', $color)->exists());
                        return $color;
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Daftarkan Relation Manager disini
            RelationManagers\UsersRelationManager::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Tim')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('color')
                    ->label('Warna'),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Jumlah Anggota'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
