<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubcategoryResource\Pages;
use App\Models\Subcategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SubcategoryResource extends Resource
{
    protected static ?string $model = Subcategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Manajemen Portal';
    protected static ?string $navigationLabel = 'Subkategori Link';
    protected static ?string $modelLabel = 'Subkategori';
    protected static ?string $slug = 'subcategori-links';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name', fn ($query) => $query->where('is_active', true)->orderBy('order'))
                    ->label('Kategori Induk')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name', fn ($query) => auth()->user()->isSuperAdmin() || auth()->user()->isKepala()
                        ? $query
                        : $query->whereIn('id', auth()->user()->getManagedTeamIds())
                    )
                    ->label('Tim Pemilik')
                    ->default(fn () => auth()->user()->teams()->wherePivot('role', 'admin')->first()?->id)
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('name')
                    ->label('Nama Subkategori')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null
                    ),

                Forms\Components\TextInput::make('slug')
                    ->label('Slug (Otomatis)')
                    ->disabled()
                    ->dehydrated()
                    ->required(),

                Forms\Components\TextInput::make('icon')
                    ->label('Icon (Opsional)')
                    ->placeholder('contoh: folder')
                    ->helperText('Nama Heroicon, kosongkan jika tidak perlu'),

                Forms\Components\TextInput::make('order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0),

                Forms\Components\Toggle::make('is_active')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Subkategori')
                    ->searchable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Tim')
                    ->sortable(),
                Tables\Columns\TextColumn::make('links_count')
                    ->label('Jumlah Link')
                    ->counts('links')
                    ->badge()
                    ->color('success'),
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status Aktif'),
            ])
            ->defaultSort('category_id')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Kategori')
                    ->preload(),
                Tables\Filters\SelectFilter::make('team_id')
                    ->relationship('team', 'name')
                    ->label('Tim')
                    ->preload(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubcategories::route('/'),
            'create' => Pages\CreateSubcategory::route('/create'),
            'edit' => Pages\EditSubcategory::route('/{record}/edit'),
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
