<?php

namespace App\Filament\Resources\Alumnos;

use App\Filament\Resources\Alumnos\Pages\CreateAlumno;
use App\Filament\Resources\Alumnos\Pages\EditAlumno;
use App\Filament\Resources\Alumnos\Pages\ListAlumnos;
use App\Filament\Resources\Alumnos\Schemas\AlumnoForm;
use App\Filament\Resources\Alumnos\Tables\AlumnosTable;
use App\Models\Alumno;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AlumnoResource extends Resource
{
    protected static ?string $model = Alumno::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'apellido';

    public static function form(Schema $schema): Schema
    {
        return AlumnoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AlumnosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAlumnos::route('/'),
            'create' => CreateAlumno::route('/create'),
            'edit' => EditAlumno::route('/{record}/edit'),
        ];
    }
}
