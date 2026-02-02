<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnnouncementResource\Pages;
use App\Models\Announcement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AnnouncementResource extends Resource
{
    protected static ?string $model = Announcement::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    // GRUP: Manajemen Portal
    protected static ?string $navigationGroup = 'Manajemen Portal';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required(),
                Forms\Components\Textarea::make('content')->required(),
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name', fn(\Illuminate\Database\Eloquent\Builder $query) => 
                        auth()->user()->isSuperAdmin() || auth()->user()->isKepala() 
                            ? $query 
                            : $query->whereIn('id', auth()->user()->getManagedTeamIds())
                    )
                    ->label('Tim (Opsional)')
                    ->helperText('Kosongkan jika untuk semua tim (Hanya Super Admin)')
                    ->disabled(fn() => !auth()->user()->isSuperAdmin() && !auth()->user()->isKepala())
                    ->default(fn() => auth()->user()->teams()->wherePivot('role', 'admin')->first()?->id),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('team.name')->label('Tim')->placeholder('Semua Tim'),
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
            'index' => Pages\ListAnnouncements::route('/'),
            'create' => Pages\CreateAnnouncement::route('/create'),
            'edit' => Pages\EditAnnouncement::route('/{record}/edit'),
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
