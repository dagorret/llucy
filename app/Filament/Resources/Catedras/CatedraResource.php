<?php

namespace App\Filament\Resources\Catedras;

use App\Filament\Resources\Catedras\Pages\CreateCatedra;
use App\Filament\Resources\Catedras\Pages\EditCatedra;
use App\Filament\Resources\Catedras\Pages\ListCatedras;
use App\Filament\Resources\Catedras\Schemas\CatedraForm;
use App\Filament\Resources\Catedras\Tables\CatedrasTable;
use App\Models\Catedra;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class CatedraResource extends Resource
{
    protected static ?string $model = Catedra::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $recordTitleAttribute = 'codigo';

    public static function form(Schema $schema): Schema
    {
        return CatedraForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CatedrasTable::configure($table);
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
            'index' => ListCatedras::route('/'),
            'create' => CreateCatedra::route('/create'),
            'edit' => EditCatedra::route('/{record}/edit'),
        ];
    }
}
