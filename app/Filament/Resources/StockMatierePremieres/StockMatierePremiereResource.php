<?php

namespace App\Filament\Resources\StockMatierePremieres;

use App\Filament\Resources\StockMatierePremieres\Pages\CreateStockMatierePremiere;
use App\Filament\Resources\StockMatierePremieres\Pages\EditStockMatierePremiere;
use App\Filament\Resources\StockMatierePremieres\Pages\ListStockMatierePremieres;
use App\Filament\Resources\StockMatierePremieres\Schemas\StockMatierePremiereForm;
use App\Filament\Resources\StockMatierePremieres\Tables\StockMatierePremieresTable;
use App\Models\StockMatierePremiere;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class StockMatierePremiereResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = StockMatierePremiere::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return StockMatierePremiereForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StockMatierePremieresTable::configure($table);
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
            'index' => ListStockMatierePremieres::route('/'),
            'create' => CreateStockMatierePremiere::route('/create'),
            'edit' => EditStockMatierePremiere::route('/{record}/edit'),
        ];
    }
}
