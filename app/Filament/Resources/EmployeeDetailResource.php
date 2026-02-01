<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeDetailResource\Pages;
use App\Models\EmployeeDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeDetailResource extends Resource
{
    protected static ?string $model = EmployeeDetail::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $pluralLabel = 'Data Pegawai';
    protected static ?string $navigationGroup = 'Kepegawaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pegawai')
                    ->description('Pastikan data NIP dan Nama sesuai.')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Akun Pegawai')
                            ->required()
                            ->searchable(),
                        Forms\Components\TextInput::make('nip')->label('NIP')->numeric(),
                        Forms\Components\TextInput::make('nik')->label('NIK')->numeric(),
                    ])->columns(3),

                Forms\Components\Section::make('Jabatan & Pangkat')
                    ->schema([
                        Forms\Components\TextInput::make('pangkat_golongan')->placeholder('III/a'),
                        Forms\Components\TextInput::make('jabatan'),
                        Forms\Components\TextInput::make('masa_kerja'),
                        Forms\Components\TextInput::make('pendidikan_terakhir'),
                    ])->columns(2),

                Forms\Components\Section::make('Data Pribadi')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('tempat_lahir'),
                        Forms\Components\DatePicker::make('tanggal_lahir'),
                        Forms\Components\Select::make('status_perkawinan')
                            ->options(['Belum Kawin'=>'Belum Kawin', 'Kawin'=>'Kawin', 'Cerai'=>'Cerai']),
                        Forms\Components\TextInput::make('nama_pasangan'),
                        Forms\Components\Textarea::make('alamat_tinggal')->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Data Rekening')
                    ->schema([
                        Forms\Components\TextInput::make('bank_name')->default('BRI'),
                        Forms\Components\TextInput::make('nomor_rekening'),
                        Forms\Components\TextInput::make('email_kantor')->email(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('nip')->label('NIP'),
                Tables\Columns\TextColumn::make('jabatan'),
                Tables\Columns\TextColumn::make('pangkat_golongan')->label('Gol'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployeeDetails::route('/'),
            'create' => Pages\CreateEmployeeDetail::route('/create'),
            'edit' => Pages\EditEmployeeDetail::route('/{record}/edit'),
        ];
    }
}