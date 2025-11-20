<?php

namespace App\Filament\Resources\Catedras\Pages;

use App\Filament\Resources\Catedras\CatedraResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCatedra extends EditRecord
{
    protected static string $resource = CatedraResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
