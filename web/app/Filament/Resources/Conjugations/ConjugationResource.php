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
                // Verb selection via relationship (shows infinitive)
                Select::make('verb_id')
                    ->label('Verb')
                    ->relationship('verb', 'infinitive')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Tense selection via relationship (shows name)
                Select::make('tense_id')
                    ->label('Tense')
                    ->relationship('tense', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                // Person with pretty labels
                Select::make('person')
                    ->label('Person')
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
                    ->label('Conjugated form')
                    ->required(),

                Toggle::make('enabled')
                    ->label('Enabled')
                    ->default(true)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('verb.infinitive')
                    ->label('Verb')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tense.name')
                    ->label('Tense')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('person')
                    ->label('Person')
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
                    ->label('Conjugated form')
                    ->searchable(),
                ToggleColumn::make('enabled')
                    ->label('Enabled')
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
                    ->label('Verb')
                    ->relationship('verb', 'infinitive')
                    ->searchable(),
                SelectFilter::make('tense')
                    ->label('Tense')
                    ->relationship('tense', 'name')
                    ->searchable(),
                TernaryFilter::make('enabled')
                    ->label('Enabled')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('enable')
                        ->label('Enable selected')
                        ->icon('heroicon-m-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['enabled' => true]);
                        }),
                    BulkAction::make('disable')
                        ->label('Disable selected')
                        ->icon('heroicon-m-x-mark')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            $records->each->update(['enabled' => false]);
                        }),
                    DeleteBulkAction::make(),
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
