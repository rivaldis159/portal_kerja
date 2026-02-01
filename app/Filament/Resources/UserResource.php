<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use App\Imports\ImportPegawai; // Pastikan import ini ada jika pakai fitur excel
use Maatwebsite\Excel\Facades\Excel; // Pastikan import ini ada
use Filament\Forms\Components\FileUpload;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Manajemen User';
    protected static ?string $navigationLabel = 'Pengguna (Login)';
    protected static ?string $modelLabel = 'Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrateStateUsing(fn($state) => Hash::make($state))
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),

                Forms\Components\Select::make('role')
                    ->label('Hak Akses (Role)')
                    ->options([
                        'super_admin' => 'Super Admin',
                        'kepala' => 'Kepala BPS',
                        'pegawai' => 'Pegawai',
                    ])
                    ->required()
                    ->helperText('Untuk Admin Tim, pilih "Pegawai" lalu atur kewenangannya di tabel "Keanggotaan Tim" di bawah.'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->label('Nama'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin_tim' => 'warning',
                        'kepala' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'super_admin' => 'Super Admin',
                        'admin_tim' => 'Admin Tim',
                        'kepala' => 'Kepala',
                        'pegawai' => 'Pegawai',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('teams.name')
                    ->label('Tim')
                    ->badge(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            // Daftarkan Relation Manager disini
            RelationManagers\TeamsRelationManager::class,
        ];
    }
}
