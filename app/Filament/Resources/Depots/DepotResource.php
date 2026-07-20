<?php

namespace App\Filament\Resources\Depots;

use App\Filament\Resources\Depots\Pages\CreateDepot;
use App\Filament\Resources\Depots\Pages\EditDepot;
use App\Filament\Resources\Depots\Pages\ListDepots;
use App\Filament\Resources\Depots\Schemas\DepotForm;
use App\Filament\Resources\Depots\Tables\DepotsTable;
use App\Models\Depot;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DepotResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = Depot::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DepotForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepotsTable::configure($table);
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
            'index' => ListDepots::route('/'),
            'create' => CreateDepot::route('/create'),
            'edit' => EditDepot::route('/{record}/edit'),
        ];
    }
}
