<?php

namespace App\Filament\Resources\Catedras\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CatedraForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('materia_id')
                    ->label('Materia')
                    ->relationship('materia', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('codigo')
                    ->label('Código')
                    ->required()
                    ->maxLength(20),
                TextInput::make('codigo_grupo')
                    ->label('Código de grupo')
                    ->maxLength(50),
                TextInput::make('codigo_canal')
                    ->label('Código de canal')
                    ->maxLength(50),
                Select::make('modalidad')
                    ->options([
                        'presencial' => 'Presencial',
                        'distancia' => 'Distancia',
                        'ambas' => 'Ambas',
                    ])
                    ->required()
                    ->default('ambas'),
            ]);
    }
}
