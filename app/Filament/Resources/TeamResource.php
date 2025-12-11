<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\ColorPicker::make('color')
                    ->default('#3b82f6'),
                Select::make('icon')
                    ->label('Pilih Icon')
                    ->searchable()
                    ->options([
                        'heroicon-o-home' => 'Home / Rumah',
                        'heroicon-o-user' => 'User / Orang',
                        'heroicon-o-users' => 'Users / Tim',
                        'heroicon-o-document-text' => 'Dokumen',
                        'heroicon-o-folder' => 'Folder',
                        'heroicon-o-briefcase' => 'Tas Kerja / Dinas',
                        'heroicon-o-chart-bar' => 'Statistik / Grafik',
                        'heroicon-o-computer-desktop' => 'Komputer / Aplikasi',
                        'heroicon-o-globe-alt' => 'Globe / Internet',
                        'heroicon-o-link' => 'Tautan / Link',
                        'heroicon-o-cloud' => 'Cloud / Awan',
                        'heroicon-o-calculator' => 'Kalkulator / Hitung',
                        'heroicon-o-calendar' => 'Kalender',
                        'heroicon-o-clipboard-document-check' => 'Ceklis / Survei',
                        // Tambahkan icon Heroicons v2 lainnya sesuai kebutuhan
                    ])
                    ->helperText('Pilih icon yang sesuai dengan tim/link ini.'),
                Forms\Components\Select::make('users')
                    ->relationship('users', 'name')
                    ->multiple()
                    ->preload()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50),
                Tables\Columns\ColorColumn::make('color'),
                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label('Members'),
                Tables\Columns\TextColumn::make('links_count')
                    ->counts('links')
                    ->label('Links'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->role !== 'super_admin') {
            $query->whereHas('users', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        return $query;
    }
}
