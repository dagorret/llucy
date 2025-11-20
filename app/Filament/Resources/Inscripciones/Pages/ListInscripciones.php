<?php

namespace App\Filament\Resources\Inscripciones\Pages;

use App\Filament\Resources\Inscripciones\InscripcionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInscripciones extends ListRecords
{
    protected static string $resource = InscripcionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
