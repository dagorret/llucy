<?php

namespace App\Filament\Resources\Catedras\Pages;

use App\Filament\Resources\Catedras\CatedraResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCatedras extends ListRecords
{
    protected static string $resource = CatedraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
