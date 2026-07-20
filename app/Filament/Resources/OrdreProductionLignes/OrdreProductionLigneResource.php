<?php

namespace App\Filament\Resources\OrdreProductionLignes;

use App\Filament\Resources\OrdreProductionLignes\Pages\CreateOrdreProductionLigne;
use App\Filament\Resources\OrdreProductionLignes\Pages\EditOrdreProductionLigne;
use App\Filament\Resources\OrdreProductionLignes\Pages\ListOrdreProductionLignes;
use App\Filament\Resources\OrdreProductionLignes\Schemas\OrdreProductionLigneForm;
use App\Filament\Resources\OrdreProductionLignes\Tables\OrdreProductionLignesTable;
use App\Models\OrdreProductionLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class OrdreProductionLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = OrdreProductionLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OrdreProductionLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OrdreProductionLignesTable::configure($table);
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
            'index' => ListOrdreProductionLignes::route('/'),
            'create' => CreateOrdreProductionLigne::route('/create'),
            'edit' => EditOrdreProductionLigne::route('/{record}/edit'),
        ];
    }
}
