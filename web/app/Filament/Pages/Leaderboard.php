<?php

namespace App\Filament\Pages;

use App\Models\Group;
use App\Models\StudentAnswer;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedTable;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class Leaderboard extends Page implements HasTable
{
    use InteractsWithTable {
        makeTable as makeBaseTable;
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $navigationLabel = 'Classement';

    protected static ?string $title = 'Classement';

    protected static ?string $slug = 'leaderboard';

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                EmbeddedTable::make(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                StudentAnswer::query()
                    ->where('is_correct', true)
                    ->join('users', 'student_answers.user_id', '=', 'users.id')
                    ->leftJoin('groups', 'users.main_group_id', '=', 'groups.id')
                    ->selectRaw('student_answers.user_id as id, users.name as user_name, groups.name as group_name, COUNT(*) as correct_count')
                    ->groupBy('student_answers.user_id', 'users.name', 'groups.name')
                    ->orderByDesc('correct_count')
            )
            ->columns([
                TextColumn::make('index')
                    ->label('#')
                    ->rowIndex(),
                TextColumn::make('user_name')
                    ->label('Nom')
                    ->searchable(query: fn (Builder $query, string $search) => $query->where('users.name', 'like', "%{$search}%")),
                TextColumn::make('group_name')
                    ->label('Classe'),
                TextColumn::make('correct_count')
                    ->label('Bonnes réponses')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('period')
                    ->label('Période')
                    ->options([
                        'all' => 'Toutes',
                        'week' => 'Cette semaine',
                    ])
                    ->default('all')
                    ->query(function (Builder $query, array $data) {
                        if (($data['value'] ?? null) === 'week') {
                            $query->where('student_answers.created_at', '>=', Carbon::now()->startOfWeek());
                        }
                    }),
                SelectFilter::make('group')
                    ->label('Classe')
                    ->options(fn () => Group::orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] ?? null) {
                            $query->where('users.main_group_id', $data['value']);
                        }
                    }),
                SelectFilter::make('year')
                    ->label('Année')
                    ->options(fn () => Group::distinct()->orderBy('year')->pluck('year', 'year')->all())
                    ->query(function (Builder $query, array $data) {
                        if ($data['value'] ?? null) {
                            $query->where('groups.year', $data['value']);
                        }
                    }),
            ])
            ->defaultPaginationPageOption(50)
            ->paginated([25, 50, 100]);
    }
}
