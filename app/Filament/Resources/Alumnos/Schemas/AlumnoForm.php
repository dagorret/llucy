<?php

namespace App\Filament\Resources\Alumnos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AlumnoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tipo_documento')
                    ->label('Tipo de documento')
                    ->options([
                        'dni' => 'DNI',
                        'pasaporte' => 'Pasaporte',
                    ])
                    ->required(),
                TextInput::make('dni')
                    ->label('Documento')
                    ->required()
                    ->maxLength(30),
                TextInput::make('nombre')
                    ->required(),
                TextInput::make('apellido')
                    ->required(),
                TextInput::make('email_personal')
                    ->email()
                    ->required(),
                TextInput::make('email_institucional')
                    ->email(),
                DatePicker::make('fecha_nacimiento')
                    ->label('Fecha de nacimiento'),
                TextInput::make('cohorte')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue((int) date('Y') + 1),
                TextInput::make('localidad'),
                TextInput::make('telefono'),
                Select::make('estado_actual')
                    ->label('Estado actual')
                    ->options([
                        'preinscripto' => 'Preinscripto',
                        'aspirante' => 'Aspirante',
                        'ingresante' => 'Ingresante',
                        'alumno' => 'Alumno',
                    ])
                    ->required()
                    ->default('preinscripto'),
                DatePicker::make('fecha_ingreso')
                    ->label('Fecha de ingreso'),
                TextInput::make('estado_ingreso')
                    ->label('Estado de ingreso'),
            ]);
    }
}
