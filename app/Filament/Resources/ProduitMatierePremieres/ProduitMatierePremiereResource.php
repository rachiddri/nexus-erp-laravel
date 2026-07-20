<?php

namespace App\Filament\Resources\ProduitMatierePremieres;

use App\Filament\Resources\ProduitMatierePremieres\Pages\CreateProduitMatierePremiere;
use App\Filament\Resources\ProduitMatierePremieres\Pages\EditProduitMatierePremiere;
use App\Filament\Resources\ProduitMatierePremieres\Pages\ListProduitMatierePremieres;
use App\Filament\Resources\ProduitMatierePremieres\Schemas\ProduitMatierePremiereForm;
use App\Filament\Resources\ProduitMatierePremieres\Tables\ProduitMatierePremieresTable;
use App\Models\ProduitMatierePremiere;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProduitMatierePremiereResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = ProduitMatierePremiere::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProduitMatierePremiereForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduitMatierePremieresTable::configure($table);
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
            'index' => ListProduitMatierePremieres::route('/'),
            'create' => CreateProduitMatierePremiere::route('/create'),
            'edit' => EditProduitMatierePremiere::route('/{record}/edit'),
        ];
    }
}
