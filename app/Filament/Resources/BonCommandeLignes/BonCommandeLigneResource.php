<?php

namespace App\Filament\Resources\BonCommandeLignes;

use App\Filament\Resources\BonCommandeLignes\Pages\CreateBonCommandeLigne;
use App\Filament\Resources\BonCommandeLignes\Pages\EditBonCommandeLigne;
use App\Filament\Resources\BonCommandeLignes\Pages\ListBonCommandeLignes;
use App\Filament\Resources\BonCommandeLignes\Schemas\BonCommandeLigneForm;
use App\Filament\Resources\BonCommandeLignes\Tables\BonCommandeLignesTable;
use App\Models\BonCommandeLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BonCommandeLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    protected static ?string $model = BonCommandeLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BonCommandeLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BonCommandeLignesTable::configure($table);
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
            'index' => ListBonCommandeLignes::route('/'),
            'create' => CreateBonCommandeLigne::route('/create'),
            'edit' => EditBonCommandeLigne::route('/{record}/edit'),
        ];
    }
}
