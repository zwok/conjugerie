<?php

namespace App\Filament\Resources\ConjugationSets;

use App\Filament\Resources\ConjugationSets\Pages\ManageConjugationSets;
use App\Models\ConjugationSet;
use App\Models\Group;
use App\Models\Tense;
use App\Models\Verb;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConjugationSetResource extends Resource
{
    protected static ?string $model = ConjugationSet::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $navigationLabel = 'Ensembles de conjugaisons';

    protected static ?string $modelLabel = 'Ensemble de conjugaisons';

    protected static ?string $pluralModelLabel = 'Ensembles de conjugaisons';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->columnSpanFull(),

                Select::make('verbs')
                    ->label('Verbes')
                    ->relationship('verbs', 'infinitive')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Sélectionnez les verbes à inclure dans cet ensemble')
                    ->columnSpanFull(),

                Select::make('tenses')
                    ->label('Temps')
                    ->relationship('tenses', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->helperText('Sélectionnez les temps à inclure dans cet ensemble')
                    ->columnSpanFull(),

                Select::make('groups')
                    ->label('Groupes')
                    ->relationship('groups', 'name')
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->helperText('Assignez cet ensemble à un ou plusieurs groupes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('verbs_count')
                    ->label('Verbes')
                    ->counts('verbs')
                    ->sortable(),

                TextColumn::make('tenses_count')
                    ->label('Temps')
                    ->counts('tenses')
                    ->sortable(),

                TextColumn::make('groups_count')
                    ->label('Groupes')
                    ->counts('groups')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageConjugationSets::route('/'),
        ];
    }
}
