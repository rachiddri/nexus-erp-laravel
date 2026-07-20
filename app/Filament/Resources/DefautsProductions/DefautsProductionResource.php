<?php

namespace App\Filament\Resources\DefautsProductions;

use App\Filament\Resources\DefautsProductions\Pages\CreateDefautsProduction;
use App\Filament\Resources\DefautsProductions\Pages\EditDefautsProduction;
use App\Filament\Resources\DefautsProductions\Pages\ListDefautsProductions;
use App\Filament\Resources\DefautsProductions\Schemas\DefautsProductionForm;
use App\Filament\Resources\DefautsProductions\Tables\DefautsProductionsTable;
use App\Models\DefautsProduction;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DefautsProductionResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = DefautsProduction::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return DefautsProductionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DefautsProductionsTable::configure($table);
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
            'index' => ListDefautsProductions::route('/'),
            'create' => CreateDefautsProduction::route('/create'),
            'edit' => EditDefautsProduction::route('/{record}/edit'),
        ];
    }
}
