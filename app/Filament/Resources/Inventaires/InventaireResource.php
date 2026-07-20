<?php

namespace App\Filament\Resources\Inventaires;

use App\Filament\Resources\Inventaires\Pages\CreateInventaire;
use App\Filament\Resources\Inventaires\Pages\EditInventaire;
use App\Filament\Resources\Inventaires\Pages\ListInventaires;
use App\Filament\Resources\Inventaires\Schemas\InventaireForm;
use App\Filament\Resources\Inventaires\Tables\InventairesTable;
use App\Models\Inventaire;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventaireResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = Inventaire::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return InventaireForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventairesTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Inventaires\RelationManagers\InventaireLignesRelationManager::class,
            \App\Filament\Resources\Inventaires\RelationManagers\InventaireProduitsPhysiquesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventaires::route('/'),
            'create' => CreateInventaire::route('/create'),
            'edit' => EditInventaire::route('/{record}/edit'),
        ];
    }
}
