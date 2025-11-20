<?php

namespace App\Filament\Resources\Inscripciones\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class InscripcionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('alumno_id')
                    ->label('Alumno')
                    ->relationship('alumno', 'apellido')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('materia_id')
                    ->label('Materia')
                    ->relationship('materia', 'nombre')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                Select::make('catedra_id')
                    ->label('Cátedra')
                    ->relationship('catedra', 'codigo')
                    ->searchable()
                    ->preload()
                    ->nullable(),
                DateTimePicker::make('fecha_inscripcion')
                    ->label('Fecha de inscripción')
                    ->default(now()),
                Select::make('estado')
                    ->options([
                        'pendiente' => 'Pendiente',
                        'confirmada' => 'Confirmada',
                        'baja' => 'Baja',
                    ])
                    ->default('confirmada')
                    ->required(),
            ]);
    }
}
