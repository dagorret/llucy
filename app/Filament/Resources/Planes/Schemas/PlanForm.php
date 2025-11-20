<?php

namespace App\Filament\Resources\Planes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(4)
                    ->columnSpan(1),
                TextInput::make('nombre')
                    ->required()
                    ->columnSpan(2),
                DatePicker::make('fecha_desde')
                    ->label('Fecha desde')
                    ->required(),
            ]);
    }
}
