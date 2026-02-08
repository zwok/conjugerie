<?php

namespace App\Filament\Resources\Groups;

use App\Filament\Resources\Groups\Pages\ManageGroups;
use App\Models\Group;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $navigationLabel = 'Groupes';

    protected static ?string $modelLabel = 'Groupe';

    protected static ?string $pluralModelLabel = 'Groupes';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required(),
                TextInput::make('code')
                    ->label('Code'),
                TextInput::make('year')
                    ->label('Année (1-7)')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(7),
                TextInput::make('platform')
                    ->label('Plateforme')
                    ->disabled(),
                TextInput::make('external_id')
                    ->label('ID externe')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable(),
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable(),
                TextColumn::make('year')
                    ->label('Année')
                    ->sortable(),
                TextColumn::make('users_count')
                    ->label('Utilisateurs')
                    ->counts('users'),
                TextColumn::make('platform')
                    ->label('Plateforme')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()->label('Modifier'),
                DeleteAction::make()->label('Supprimer'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('Supprimer la sélection'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageGroups::route('/'),
        ];
    }
}
