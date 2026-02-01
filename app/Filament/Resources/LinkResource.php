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
                                    ->relationship('team', 'name')
                                    ->default(fn() => auth()->user()->team_id)
                                    ->label('Tim Pemilik')
                                    ->required(),
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->label('Kategori')
                                    ->required(),
                                Forms\Components\TextInput::make('title')->required(),
                                Forms\Components\TextInput::make('url')->url()->required(),
                                Forms\Components\Select::make('target')
                                    ->options(['_blank' => 'Tab Baru', '_self' => 'Tab Sama'])
                                    ->default('_blank'),
                            ])
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Opsi Akses')
                            ->schema([
                                Forms\Components\Toggle::make('is_active')->default(true),
                                Forms\Components\Toggle::make('is_public')->label('Publik (Tim Lain Bisa Lihat)'),
                                Forms\Components\Toggle::make('is_vpn_required')->label('Wajib VPN')->onColor('danger'),
                                Forms\Components\Toggle::make('is_bps_pusat')
                                    ->label('Link Pusat')
                                    ->visible(fn () => auth()->user()?->isSuperAdmin() ?? false),
                            ])
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->badge(),
                Tables\Columns\TextColumn::make('team.name')->label('Tim'),
                
                // Badge Status
                Tables\Columns\IconColumn::make('is_vpn_required')
                    ->label('VPN')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->trueColor('danger'),
                    
                Tables\Columns\ToggleColumn::make('is_active'),
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
}