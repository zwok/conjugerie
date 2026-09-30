<?php

namespace App\Filament\Resources\ConjugationSets\Pages;

use App\Filament\Resources\ConjugationSets\ConjugationSetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConjugationSets extends ManageRecords
{
    protected static string $resource = ConjugationSetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Créer un ensemble'),
        ];
    }
}
