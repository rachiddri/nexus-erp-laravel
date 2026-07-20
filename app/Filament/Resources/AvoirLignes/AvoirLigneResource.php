<?php

namespace App\Filament\Resources\AvoirLignes;

use App\Filament\Resources\AvoirLignes\Pages\CreateAvoirLigne;
use App\Filament\Resources\AvoirLignes\Pages\EditAvoirLigne;
use App\Filament\Resources\AvoirLignes\Pages\ListAvoirLignes;
use App\Filament\Resources\AvoirLignes\Schemas\AvoirLigneForm;
use App\Filament\Resources\AvoirLignes\Tables\AvoirLignesTable;
use App\Models\AvoirLigne;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AvoirLigneResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Facturation';

    protected static ?string $model = AvoirLigne::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AvoirLigneForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AvoirLignesTable::configure($table);
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
            'index' => ListAvoirLignes::route('/'),
            'create' => CreateAvoirLigne::route('/create'),
            'edit' => EditAvoirLigne::route('/{record}/edit'),
        ];
    }
}
