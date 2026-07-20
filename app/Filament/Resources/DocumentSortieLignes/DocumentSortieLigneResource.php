<?php

namespace App\Filament\Resources\DocumentSortieLignes;

use App\Filament\Resources\DocumentSortieLignes\Pages\CreateDocumentSortieLigne;
use App\Filament\Resources\DocumentSortieLignes\Pages\EditDocumentSortieLigne;
use App\Filament\Resources\DocumentSortieLignes\Pages\ListDocumentSortieLignes;
use App\Filament\Resources\DocumentSortieLignes\Schemas\DocumentSortieLigneForm;
use App\Filament\Resources\DocumentSortieLignes\Tables\DocumentSortieLignesTable;
use App\Models\DocumentSortieLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentSortieLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Documents de sortie';

    protected static ?string $model = DocumentSortieLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DocumentSortieLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentSortieLignesTable::configure($table);
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
            'index' => ListDocumentSortieLignes::route('/'),
            'create' => CreateDocumentSortieLigne::route('/create'),
            'edit' => EditDocumentSortieLigne::route('/{record}/edit'),
        ];
    }
}
