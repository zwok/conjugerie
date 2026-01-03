<?php

namespace App\Filament\Resources\Conjugations\Pages;

use App\Filament\Resources\Conjugations\ConjugationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConjugations extends ManageRecords
{
    protected static string $resource = ConjugationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
