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

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Manajemen Pegawai';

    protected static ?string $pluralLabel = 'Data Pegawai';

    protected static ?string $navigationGroup = 'Kepegawaian';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pegawai')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('Akun User')
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('nip')
                            ->label('NIP Baru (18 Digit)')
                            ->minLength(18)->maxLength(18)
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $nip = $state;
                                $pangkat = $get('pangkat_golongan');
                                if (! $nip || strlen($nip) != 18) {
                                    return;
                                }
                                $isPPPK = $pangkat && str_contains($pangkat, 'PPPK');

                                try {
                                    if ($isPPPK) {
                                        $startYear = (int) substr($nip, 8, 4);
                                        $years = max(0, (int) date('Y') - $startYear);
                                        $set('masa_kerja_tahun', $years);
                                        $set('masa_kerja_bulan', 0);
                                    } else {
                                        $tmtString = substr($nip, 8, 6);
                                        $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);
                                        $diff = $tmtDate->diff(\Carbon\Carbon::now());
                                        $set('masa_kerja_tahun', $diff->y);
                                        $set('masa_kerja_bulan', $diff->m);
                                    }
                                } catch (\Exception $e) {
                                }
                            }),
                        Forms\Components\TextInput::make('nip_lama')
                            ->label('NIP Lama (9 Digit)')
                            ->minLength(9)->maxLength(9),
                    ])->columns(3),

                Forms\Components\Section::make('Pangkat & Jabatan')
                    ->schema([
                        Forms\Components\Select::make('pangkat_golongan')
                            ->label('Pangkat/Golongan')
                            ->options(EmployeeDetail::getPangkatOptions())
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                $nip = $get('nip');
                                if (! $nip || strlen($nip) != 18) {
                                    return;
                                }

                                $isPPPK = str_contains($state, 'PPPK');

                                try {
                                    if ($isPPPK) {
                                        $startYear = (int) substr($nip, 8, 4);
                                        $years = max(0, (int) date('Y') - $startYear);
                                        $set('masa_kerja_tahun', $years);
                                        $set('masa_kerja_bulan', 0);
                                    } else {
                                        $tmtString = substr($nip, 8, 6);
                                        $tmtDate = \Carbon\Carbon::createFromFormat('Ym', $tmtString);
                                        $diff = $tmtDate->diff(\Carbon\Carbon::now());
                                        $set('masa_kerja_tahun', $diff->y);
                                        $set('masa_kerja_bulan', $diff->m);
                                    }
                                } catch (\Exception $e) {
                                }
                            }),

                        Forms\Components\DatePicker::make('tmt_pangkat')
                            ->label('TMT Pangkat'),

                        Forms\Components\Select::make('jabatan')
                            ->label('Jabatan')
                            ->options(EmployeeDetail::getJabatanOptions())
                            ->searchable(),

                        Forms\Components\Group::make([
                            Forms\Components\TextInput::make('masa_kerja_tahun')
                                ->label('MK Tahun')
                                ->numeric()
                                ->readOnly(),
                            Forms\Components\TextInput::make('masa_kerja_bulan')
                                ->label('MK Bulan')
                                ->numeric()
                                ->readOnly(),
                        ])->columns(2)->label('Masa Kerja'),
                    ])->columns(2),

                Forms\Components\Section::make('Pendidikan')
                    ->schema([
                        Forms\Components\Select::make('pendidikan_strata')
                            ->options([
                                'SMA/SMK' => 'SMA/SMK', 'D-I' => 'D-I', 'D-II' => 'D-II', 'D-III' => 'D-III',
                                'D-IV' => 'D-IV', 'S-1' => 'S-1', 'S-2' => 'S-2', 'S-3' => 'S-3',
                            ]),
                        Forms\Components\TextInput::make('pendidikan_jurusan')
                            ->placeholder('Contoh: Statistika'),
                    ])->columns(2),

                Forms\Components\Section::make('Data Pribadi')
                    ->collapsed()
                    ->schema([
                        Forms\Components\TextInput::make('tempat_lahir'),
                        Forms\Components\DatePicker::make('tanggal_lahir'),
                        Forms\Components\TextInput::make('nik')->label('NIK KTP'),
                        Forms\Components\Select::make('status_perkawinan')
                            ->options(['Belum Kawin' => 'Belum Kawin', 'Kawin' => 'Kawin', 'Cerai Hidup' => 'Cerai Hidup', 'Cerai Mati' => 'Cerai Mati']),
                        Forms\Components\TextInput::make('nama_pasangan'),
                        Forms\Components\Textarea::make('alamat_tinggal')->columnSpanFull(),
                    ])->columns(3),

                Forms\Components\Section::make('Rekening Bank')
                    ->schema([
                        Forms\Components\TextInput::make('bank_name')->default('BRI'),
                        Forms\Components\TextInput::make('nomor_rekening'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Nama')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('nip')->label('NIP')->searchable(),
                Tables\Columns\TextColumn::make('jabatan')->sortable()->limit(30),
                Tables\Columns\TextColumn::make('pangkat_golongan')->label('Gol'),
                Tables\Columns\TextColumn::make('user.email')->label('Email'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
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
