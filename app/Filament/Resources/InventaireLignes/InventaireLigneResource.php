<?php

namespace App\Filament\Resources\InventaireLignes;

use App\Filament\Resources\InventaireLignes\Pages\CreateInventaireLigne;
use App\Filament\Resources\InventaireLignes\Pages\EditInventaireLigne;
use App\Filament\Resources\InventaireLignes\Pages\ListInventaireLignes;
use App\Filament\Resources\InventaireLignes\Schemas\InventaireLigneForm;
use App\Filament\Resources\InventaireLignes\Tables\InventaireLignesTable;
use App\Models\InventaireLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventaireLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = InventaireLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return InventaireLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventaireLignesTable::configure($table);
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
            'index' => ListInventaireLignes::route('/'),
            'create' => CreateInventaireLigne::route('/create'),
            'edit' => EditInventaireLigne::route('/{record}/edit'),
        ];
    }
}
