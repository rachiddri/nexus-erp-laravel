<?php

namespace App\Filament\Resources\DocumentsSorties;

use App\Filament\Resources\DocumentsSorties\Pages\CreateDocumentsSortie;
use App\Filament\Resources\DocumentsSorties\Pages\EditDocumentsSortie;
use App\Filament\Resources\DocumentsSorties\Pages\ListDocumentsSorties;
use App\Filament\Resources\DocumentsSorties\Schemas\DocumentsSortieForm;
use App\Filament\Resources\DocumentsSorties\Tables\DocumentsSortiesTable;
use App\Models\DocumentsSortie;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DocumentsSortieResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Documents de sortie';

    protected static ?string $model = DocumentsSortie::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DocumentsSortieForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DocumentsSortiesTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\DocumentsSorties\RelationManagers\DocumentSortieLignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListDocumentsSorties::route('/'),
            'create' => CreateDocumentsSortie::route('/create'),
            'edit' => EditDocumentsSortie::route('/{record}/edit'),
        ];
    }
}
