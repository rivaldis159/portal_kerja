<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LinkResource\Pages;
use App\Models\Link;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LinkResource extends Resource
{
    protected static ?string $model = Link::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Manajemen Portal';

    protected static ?string $navigationLabel = 'Kelola Link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Info Link')
                            ->schema([
                                Forms\Components\Select::make('team_id')
                                    ->relationship('team', 'name', fn (\Illuminate\Database\Eloquent\Builder $query) => auth()->user()->isSuperAdmin() || auth()->user()->isKepala()
                                            ? $query
                                            : $query->whereIn('id', auth()->user()->getManagedTeamIds())
                                    )
                                    ->label('Tim Pemilik')
                                    ->default(fn () => auth()->user()->teams()->wherePivot('role', 'admin')->first()?->id)
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name', fn ($query) => $query->where('is_active', true)->orderBy('order'))
                                    ->label('Kategori')
                                    ->required()
                                    ->live()
                                    ->searchable()
                                    ->preload()
                                    ->afterStateUpdated(fn (Forms\Set $set) => $set('subcategory_id', null)),
                                Forms\Components\Select::make('subcategory_id')
                                    ->label('Subkategori')
                                    ->options(function (Forms\Get $get) {
                                        $categoryId = $get('category_id');
                                        if (!$categoryId) return [];
                                        return \App\Models\Subcategory::where('category_id', $categoryId)
                                            ->where('is_active', true)
                                            ->orderBy('order')
                                            ->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'Sistem' => 'Sistem / Aplikasi',
                                        'Arsip' => 'Arsip / Dokumen',
                                    ])
                                    ->default('Sistem')
                                    ->label('Tipe Link')
                                    ->required(),
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\TextInput::make('url')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        // Allow relative paths (starting with /)
                                        if ($state && ! str_starts_with($state, '/') && ! preg_match('/^https?:\/\//', $state)) {
                                            $set('url', 'https://'.$state);
                                        }
                                    })
                                    ->unique(modifyRuleUsing: function ($rule, $get) {
                                        return $rule->where('team_id', $get('team_id'));
                                    }, ignoreRecord: true)
                                    ->validationMessages([
                                        'unique' => 'Link dengan URL ini sudah ada di Tim yang dipilih.',
                                    ]),
                                Forms\Components\Select::make('target')
                                    ->options(['_blank' => 'Tab Baru', '_self' => 'Tab Sama'])
                                    ->default('_blank'),
                                Forms\Components\Textarea::make('description')
                                    ->label('Deskripsi Singkat')
                                    ->rows(3)
                                    ->placeholder('Tambahkan keterangan untuk memudahkan pencarian...')
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('logo')
                                    ->label('Logo / Ikon')
                                    ->helperText('Upload logo untuk ditampilkan di halaman PODA publik (PNG/JPG/SVG, maks 512KB)')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->image()
                                    ->directory('links-logos')
                                    ->maxSize(512)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Opsi Akses')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')->label('Aktif')->default(true),
                                Forms\Components\Toggle::make('is_public')->label('Publik'),
                                Forms\Components\Toggle::make('is_vpn_required')->label('Wajib VPN')->onColor('danger'),
                                Forms\Components\Toggle::make('is_bps_pusat')
                                    ->label('Link Pusat')
                                    ->visible(fn () => auth()->user()?->isSuperAdmin() || auth()->user()?->isKepala() || auth()->user()?->isAdminTim()),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->label('Nama Link'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->label('Tipe Link')
                    ->color(fn (string $state): string => match ($state) {
                        'Sistem' => 'success',
                        'Arsip' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge(),
                Tables\Columns\TextColumn::make('subcategory.name')
                    ->label('Subkategori')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Tim'),

                Tables\Columns\IconColumn::make('is_bps_pusat')
                    ->label('Link Pusat')
                    ->boolean()
                    ->trueIcon('heroicon-o-building-library')
                    ->trueColor('info'),

                Tables\Columns\IconColumn::make('is_vpn_required')
                    ->label('VPN')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->trueColor('info'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLinks::route('/'),
            'create' => Pages\CreateLink::route('/create'),
            'edit' => Pages\EditLink::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->isSuperAdmin() || auth()->user()->isKepala()) {
            return $query;
        }

        return $query->whereIn('team_id', auth()->user()->getManagedTeamIds());
    }
}
