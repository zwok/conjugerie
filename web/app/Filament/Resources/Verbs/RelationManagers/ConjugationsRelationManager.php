<?php

namespace App\Filament\Resources\Verbs\RelationManagers;

use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ConjugationsRelationManager extends RelationManager
{
    protected static string $relationship = 'conjugations';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tense_id')
                    ->label('Temps')
                    ->relationship('tense', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

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
                    ->required(),

                TextInput::make('conjugated_form')
                    ->label('Forme conjuguée')
                    ->required(),

                Toggle::make('enabled')
                    ->label('Activé')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('conjugated_form')
            ->columns([
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
            ])
            ->filters([
                SelectFilter::make('tense')
                    ->label('Temps')
                    ->relationship('tense', 'name')
                    ->searchable(),
                TernaryFilter::make('enabled')
                    ->label('Activé')
                    ->boolean(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
}
