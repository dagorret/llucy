<?php

namespace App\Filament\Resources\Inscripciones\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InscripcionesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.apellido')
                    ->label('Alumno')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->searchable(),
                TextColumn::make('catedra.codigo')
                    ->label('Cátedra')
                    ->searchable(),
                TextColumn::make('estado')
                    ->badge()
                    ->sortable(),
                TextColumn::make('fecha_inscripcion')
                    ->label('Fecha')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
