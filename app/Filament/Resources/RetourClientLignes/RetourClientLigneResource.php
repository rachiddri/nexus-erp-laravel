<?php

namespace App\Filament\Resources\RetourClientLignes;

use App\Filament\Resources\RetourClientLignes\Pages\CreateRetourClientLigne;
use App\Filament\Resources\RetourClientLignes\Pages\EditRetourClientLigne;
use App\Filament\Resources\RetourClientLignes\Pages\ListRetourClientLignes;
use App\Filament\Resources\RetourClientLignes\Schemas\RetourClientLigneForm;
use App\Filament\Resources\RetourClientLignes\Tables\RetourClientLignesTable;
use App\Models\RetourClientLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RetourClientLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Retours clients';

    protected static ?string $model = RetourClientLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RetourClientLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RetourClientLignesTable::configure($table);
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
            'index' => ListRetourClientLignes::route('/'),
            'create' => CreateRetourClientLigne::route('/create'),
            'edit' => EditRetourClientLigne::route('/{record}/edit'),
        ];
    }
}
