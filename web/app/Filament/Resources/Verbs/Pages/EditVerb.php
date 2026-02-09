<?php

namespace App\Filament\Resources\Verbs\Pages;

use App\Filament\Resources\Verbs\VerbResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditVerb extends EditRecord
{
    protected static string $resource = VerbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()->label('Supprimer'),
        ];
    }
}
