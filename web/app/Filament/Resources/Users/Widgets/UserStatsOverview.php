<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\StudentAnswer;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Model;

class UserStatsOverview extends StatsOverviewWidget
{
    public ?Model $record = null;

    protected function getStats(): array
    {
        $totalAnswers = $this->record->studentAnswers()->count();
        $correctAnswers = $this->record->studentAnswers()->where('is_correct', true)->count();
        $successPercentage = $totalAnswers > 0
            ? round(($correctAnswers / $totalAnswers) * 100, 1)
            : 0;

        return [
            Stat::make('Réponses totales', $totalAnswers),
            Stat::make('Réponses correctes', $correctAnswers),
            Stat::make('Taux de réussite', $successPercentage . '%'),
        ];
    }

}
