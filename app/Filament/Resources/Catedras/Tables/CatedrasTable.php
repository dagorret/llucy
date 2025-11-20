<?php

namespace App\Filament\Resources\Catedras\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Filament\Tables\Table;

class CatedrasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('codigo')
            ->columns([
                TextColumn::make('materia.nombre')
                    ->label('Materia')
                    ->searchable(),
                TextColumn::make('codigo')
                    ->label('Código')
                    ->searchable(),
                TextColumn::make('modalidad')
                    ->badge()
                    ->colors([
                        'primary',
                        'success' => 'ambas',
                        'warning' => 'distancia',
                    ]),
                TextColumn::make('codigo_grupo')
                    ->label('Grupo')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('codigo_canal')
                    ->label('Canal')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('modalidad')
                    ->options([
                        'presencial' => 'Presencial',
                        'distancia' => 'Distancia',
                        'ambas' => 'Ambas',
                    ])
                    ->label('Modalidad'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Exportar')
                    ->color('primary')
                    ->defaultFormat('xlsx'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
