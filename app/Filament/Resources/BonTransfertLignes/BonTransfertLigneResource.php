<?php

namespace App\Filament\Resources\BonTransfertLignes;

use App\Filament\Resources\BonTransfertLignes\Pages\CreateBonTransfertLigne;
use App\Filament\Resources\BonTransfertLignes\Pages\EditBonTransfertLigne;
use App\Filament\Resources\BonTransfertLignes\Pages\ListBonTransfertLignes;
use App\Filament\Resources\BonTransfertLignes\Schemas\BonTransfertLigneForm;
use App\Filament\Resources\BonTransfertLignes\Tables\BonTransfertLignesTable;
use App\Models\BonTransfertLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BonTransfertLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = BonTransfertLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BonTransfertLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BonTransfertLignesTable::configure($table);
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
            'index' => ListBonTransfertLignes::route('/'),
            'create' => CreateBonTransfertLigne::route('/create'),
            'edit' => EditBonTransfertLigne::route('/{record}/edit'),
        ];
    }
}
