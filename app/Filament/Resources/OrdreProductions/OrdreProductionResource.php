<?php

namespace App\Filament\Resources\OrdreProductions;

use App\Filament\Resources\OrdreProductions\Pages\CreateOrdreProduction;
use App\Filament\Resources\OrdreProductions\Pages\EditOrdreProduction;
use App\Filament\Resources\OrdreProductions\Pages\ListOrdreProductions;
use App\Filament\Resources\OrdreProductions\Schemas\OrdreProductionForm;
use App\Filament\Resources\OrdreProductions\Tables\OrdreProductionsTable;
use App\Models\OrdreProduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrdreProductionResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = OrdreProduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrdreProductionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdreProductionsTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\OrdreProductions\RelationManagers\OrdreProductionLignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrdreProductions::route('/'),
            'create' => CreateOrdreProduction::route('/create'),
            'edit' => EditOrdreProduction::route('/{record}/edit'),
        ];
    }
}
