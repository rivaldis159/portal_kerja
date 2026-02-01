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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->default(auth()->user()->team_id)
                    ->required(),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\TextInput::make('url')->url()->required(),
                Forms\Components\Select::make('target')
                    ->options([
                        '_blank' => 'Tab Baru',
                        '_self' => 'Tab Sama',
                    ])->default('_blank'),
                
                // Fitur Baru
                Forms\Components\Section::make('Opsi Tampilan')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')->default(true),
                        Forms\Components\Toggle::make('is_public')->label('Publik (Tim Lain Bisa Lihat)'),
                        Forms\Components\Toggle::make('is_vpn_required')->label('Wajib VPN')->onColor('danger'),
                        Forms\Components\Toggle::make('is_bps_pusat')
                            ->label('Link Pusat (Muncul di Semua User)')
                            ->visible(fn () => auth()->user()->isSuperAdmin()),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('team.name')->label('Tim')->sortable(),
                Tables\Columns\IconColumn::make('is_public')->boolean()->label('Publik'),
                
                // Badge VPN Keren
                Tables\Columns\IconColumn::make('is_vpn_required')
                    ->label('VPN')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->trueColor('danger')
                    ->falseIcon('heroicon-o-lock-open')
                    ->falseColor('success'),

                Tables\Columns\ToggleColumn::make('is_active'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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