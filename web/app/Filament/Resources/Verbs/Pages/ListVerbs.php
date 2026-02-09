<?php

namespace App\Filament\Resources\Verbs\Pages;

use App\Filament\Resources\Verbs\VerbResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVerbs extends ListRecords
{
    protected static string $resource = VerbResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
