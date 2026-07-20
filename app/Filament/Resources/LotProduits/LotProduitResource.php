<?php

namespace App\Filament\Resources\LotProduits;

use App\Filament\Resources\LotProduits\Pages\CreateLotProduit;
use App\Filament\Resources\LotProduits\Pages\EditLotProduit;
use App\Filament\Resources\LotProduits\Pages\ListLotProduits;
use App\Filament\Resources\LotProduits\Schemas\LotProduitForm;
use App\Filament\Resources\LotProduits\Tables\LotProduitsTable;
use App\Models\LotProduit;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LotProduitResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = LotProduit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LotProduitForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LotProduitsTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\LotProduits\RelationManagers\DefautsProductionsRelationManager::class,
            \App\Filament\Resources\LotProduits\RelationManagers\LotConsommationMatieresRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLotProduits::route('/'),
            'create' => CreateLotProduit::route('/create'),
            'edit' => EditLotProduit::route('/{record}/edit'),
        ];
    }
}
