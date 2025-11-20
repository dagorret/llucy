<?php

namespace App\Filament\Resources\Carreras\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CarreraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->label('Plan')
                    ->relationship('plan', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(20),
                TextInput::make('nombre')
                    ->required(),
            ]);
    }
}
