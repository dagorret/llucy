<?php

namespace App\Filament\Resources\Alumnos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Illuminate\Database\Eloquent\Builder;

class AlumnosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('apellido')
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
                SelectFilter::make('estado_actual')
                    ->label('Estado')
                    ->options([
                        'preinscripto' => 'Preinscripto',
                        'aspirante' => 'Aspirante',
                        'ingresante' => 'Ingresante',
                        'alumno' => 'Alumno',
                    ]),
                Filter::make('fecha_ingreso')
                    ->label('Fecha de ingreso')
                    ->form([
                        \Filament\Forms\Components\DatePicker::make('desde')->label('Desde'),
                        \Filament\Forms\Components\DatePicker::make('hasta')->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['desde'] ?? null, fn ($q, $date) => $q->whereDate('fecha_ingreso', '>=', $date))
                            ->when($data['hasta'] ?? null, fn ($q, $date) => $q->whereDate('fecha_ingreso', '<=', $date));
                    }),
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
