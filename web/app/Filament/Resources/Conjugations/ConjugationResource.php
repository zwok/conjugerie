<?php

namespace App\Filament\Resources\Conjugations;

use App\Filament\Resources\Conjugations\Pages\ManageConjugations;
use App\Models\Conjugation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rule;
use Filament\Actions\BulkAction;
use Illuminate\Support\Collection;

class ConjugationResource extends Resource
{
    protected static ?string $model = Conjugation::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // Sélection du verbe via la relation (affiche l'infinitif)
                Select::make('verb_id')
                    ->label('Verbe')
                    ->relationship('verb', 'infinitive')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Sélection du temps via la relation (affiche le nom)
                Select::make('tense_id')
                    ->label('Temps')
                    ->relationship('tense', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Personne avec libellés lisibles
                Select::make('person')
                    ->label('Personne')
                    ->options([
                        'je' => 'Je',
                        'tu' => 'Tu',
                        'il_elle_on' => 'Il/Elle/On',
                        'nous' => 'Nous',
                        'vous' => 'Vous',
                        'ils_elles' => 'Ils/Elles',
                    ])
                    ->required()
                   ,

                TextInput::make('conjugated_form')
                    ->label('Forme conjuguée')
                    ->required(),

                Toggle::make('enabled')
                    ->label('Activé')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('verb.infinitive')
                    ->label('Verbe')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tense.name')
                    ->label('Temps')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('person')
                    ->label('Personne')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'je' => 'Je',
                        'tu' => 'Tu',
                        'il_elle_on' => 'Il/Elle/On',
                        'nous' => 'Nous',
                        'vous' => 'Vous',
                        'ils_elles' => 'Ils/Elles',
                        default => $state,
                    })
                    ->sortable(),
                TextColumn::make('conjugated_form')
                    ->label('Forme conjuguée')
                    ->searchable(),
                ToggleColumn::make('enabled')
                    ->label('Activé')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('verb')
                    ->label('Verbe')
                    ->relationship('verb', 'infinitive')
                    ->searchable(),
                SelectFilter::make('tense')
                    ->label('Temps')
                    ->relationship('tense', 'name')
                    ->searchable(),
                TernaryFilter::make('enabled')
                    ->label('Activé')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make()->label('Modifier'),
                DeleteAction::make()->label('Supprimer'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('enable')
                        ->label('Activer la sélection')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['enabled' => true]);
                        }),
                    BulkAction::make('disable')
                        ->label('Désactiver la sélection')
                        ->icon('heroicon-m-x-mark')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['enabled' => false]);
                        }),
                    DeleteBulkAction::make()->label('Supprimer la sélection'),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageConjugations::route('/'),
        ];
    }
}
