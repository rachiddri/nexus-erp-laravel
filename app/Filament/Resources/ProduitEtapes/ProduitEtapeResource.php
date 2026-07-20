<?php

namespace App\Filament\Resources\ProduitEtapes;

use App\Filament\Resources\ProduitEtapes\Pages\CreateProduitEtape;
use App\Filament\Resources\ProduitEtapes\Pages\EditProduitEtape;
use App\Filament\Resources\ProduitEtapes\Pages\ListProduitEtapes;
use App\Filament\Resources\ProduitEtapes\Schemas\ProduitEtapeForm;
use App\Filament\Resources\ProduitEtapes\Tables\ProduitEtapesTable;
use App\Models\ProduitEtape;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProduitEtapeResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = ProduitEtape::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProduitEtapeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduitEtapesTable::configure($table);
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
            'index' => ListProduitEtapes::route('/'),
            'create' => CreateProduitEtape::route('/create'),
            'edit' => EditProduitEtape::route('/{record}/edit'),
        ];
    }
}
