<?php

namespace App\Filament\Resources\Materias\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MateriaForm
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
                Select::make('carreras')
                    ->label('Carreras')
                    ->relationship('carreras', 'nombre')
                    ->multiple()
                    ->searchable()
                    ->preload(),
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(20),
                TextInput::make('codigo_uti')
                    ->label('Código UTI')
                    ->maxLength(50),
                TextInput::make('cuatrimestre')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(3)
                    ->required(),
            ]);
    }
}
