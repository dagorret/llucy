<?php

namespace App\Filament\Resources\Inscripciones;

use App\Filament\Resources\Inscripciones\Pages\CreateInscripcion;
use App\Filament\Resources\Inscripciones\Pages\EditInscripcion;
use App\Filament\Resources\Inscripciones\Pages\ListInscripciones;
use App\Filament\Resources\Inscripciones\Schemas\InscripcionForm;
use App\Filament\Resources\Inscripciones\Tables\InscripcionesTable;
use App\Models\Inscripcion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class InscripcionResource extends Resource
{
    protected static ?string $model = Inscripcion::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return InscripcionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InscripcionesTable::configure($table);
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
            'index' => ListInscripciones::route('/'),
            'create' => CreateInscripcion::route('/create'),
            'edit' => EditInscripcion::route('/{record}/edit'),
        ];
    }
}
