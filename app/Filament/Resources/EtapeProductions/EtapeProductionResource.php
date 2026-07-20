<?php

namespace App\Filament\Resources\EtapeProductions;

use App\Filament\Resources\EtapeProductions\Pages\CreateEtapeProduction;
use App\Filament\Resources\EtapeProductions\Pages\EditEtapeProduction;
use App\Filament\Resources\EtapeProductions\Pages\ListEtapeProductions;
use App\Filament\Resources\EtapeProductions\Schemas\EtapeProductionForm;
use App\Filament\Resources\EtapeProductions\Tables\EtapeProductionsTable;
use App\Models\EtapeProduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EtapeProductionResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = EtapeProduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return EtapeProductionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EtapeProductionsTable::configure($table);
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
            'index' => ListEtapeProductions::route('/'),
            'create' => CreateEtapeProduction::route('/create'),
            'edit' => EditEtapeProduction::route('/{record}/edit'),
        ];
    }
}
