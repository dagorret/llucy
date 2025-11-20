<?php

namespace App\Filament\Resources\Materias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MateriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('plan.codigo')
                    ->label('Plan')
                    ->searchable(),
                TextColumn::make('carreras_list')
                    ->label('Carreras')
                    ->state(fn ($record) => $record->carreras->pluck('nombre')->join(', '))
                    ->wrap(),
                TextColumn::make('nombre')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('codigo_uti')
                    ->label('Código UTI')
                    ->searchable(),
                TextColumn::make('cuatrimestre')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
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
