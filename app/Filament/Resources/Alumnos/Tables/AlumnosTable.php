<?php

namespace App\Filament\Resources\Alumnos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AlumnosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('apellido')
                    ->searchable(),
                TextColumn::make('nombre')
                    ->searchable(),
                TextColumn::make('tipo_documento')
                    ->label('Tipo doc')
                    ->sortable(),
                TextColumn::make('dni')
                    ->searchable(),
                TextColumn::make('email_personal')
                    ->label('Email personal')
                    ->searchable(),
                TextColumn::make('estado_actual')
                    ->badge()
                    ->sortable(),
                TextColumn::make('cohorte')
                    ->sortable(),
                TextColumn::make('fecha_ingreso')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
