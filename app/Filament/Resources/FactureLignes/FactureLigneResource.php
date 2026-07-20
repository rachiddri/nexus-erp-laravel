<?php

namespace App\Filament\Resources\FactureLignes;

use App\Filament\Resources\FactureLignes\Pages\CreateFactureLigne;
use App\Filament\Resources\FactureLignes\Pages\EditFactureLigne;
use App\Filament\Resources\FactureLignes\Pages\ListFactureLignes;
use App\Filament\Resources\FactureLignes\Schemas\FactureLigneForm;
use App\Filament\Resources\FactureLignes\Tables\FactureLignesTable;
use App\Models\FactureLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactureLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Facturation';

    protected static ?string $model = FactureLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FactureLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FactureLignesTable::configure($table);
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
            'index' => ListFactureLignes::route('/'),
            'create' => CreateFactureLigne::route('/create'),
            'edit' => EditFactureLigne::route('/{record}/edit'),
        ];
    }
}
