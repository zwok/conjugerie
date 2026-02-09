<?php

namespace App\Filament\Resources\Users\RelationManagers;

use App\Models\Verb;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'studentAnswers';

    protected static ?string $title = 'Réponses';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->leftJoin('conjugations', 'student_answers.conjugation_id', '=', 'conjugations.id')
                ->leftJoin('verbs', 'conjugations.verb_id', '=', 'verbs.id')
                ->leftJoin('tenses', 'conjugations.tense_id', '=', 'tenses.id')
                ->select([
                    'student_answers.*',
                    'verbs.infinitive as verb_infinitive',
                    'tenses.name as tense_name',
                    'conjugations.person as conjugation_person',
                    'conjugations.conjugated_form as expected_form',
                ])
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('verb_infinitive')
                    ->label('Verbe')
                    ->state(fn ($record) => $record->verb_infinitive)
                    ->placeholder('-')
                    ->searchable(query: fn ($query, string $search) => $query->where('verbs.infinitive', 'like', "%{$search}%"))
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('verbs.infinitive', $direction)),
                TextColumn::make('tense_name')
                    ->label('Temps')
                    ->state(fn ($record) => $record->tense_name)
                    ->placeholder('-')
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('tenses.name', $direction)),
                TextColumn::make('conjugation_person')
                    ->label('Personne')
                    ->state(fn ($record) => match ($record->conjugation_person) {
                        'je' => 'Je',
                        'tu' => 'Tu',
                        'il_elle_on' => 'Il/Elle/On',
                        'nous' => 'Nous',
                        'vous' => 'Vous',
                        'ils_elles' => 'Ils/Elles',
                        default => $record->conjugation_person,
                    })
                    ->placeholder('-')
                    ->sortable(query: fn ($query, string $direction) => $query->orderBy('conjugations.person', $direction)),
                TextColumn::make('expected_form')
                    ->label('Forme attendue')
                    ->state(fn ($record) => $record->expected_form)
                    ->placeholder('-'),
                TextColumn::make('student_answer')
                    ->label('Réponse élève'),
                IconColumn::make('is_correct')
                    ->label('Correct')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('-'),
            ])
            ->filters([
                SelectFilter::make('verb')
                    ->label('Verbe')
                    ->options(fn () => Verb::orderBy('infinitive')->pluck('infinitive', 'id'))
                    ->query(function ($query, array $data) {
                        if (filled($data['value'])) {
                            $query->where('conjugations.verb_id', $data['value']);
                        }
                    })
                    ->searchable()
                    ->preload(),
            ]);
    }
}
