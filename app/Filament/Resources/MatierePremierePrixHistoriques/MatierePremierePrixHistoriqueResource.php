<?php

namespace App\Filament\Resources\MatierePremierePrixHistoriques;

use App\Filament\Resources\MatierePremierePrixHistoriques\Pages\CreateMatierePremierePrixHistorique;
use App\Filament\Resources\MatierePremierePrixHistoriques\Pages\EditMatierePremierePrixHistorique;
use App\Filament\Resources\MatierePremierePrixHistoriques\Pages\ListMatierePremierePrixHistoriques;
use App\Filament\Resources\MatierePremierePrixHistoriques\Schemas\MatierePremierePrixHistoriqueForm;
use App\Filament\Resources\MatierePremierePrixHistoriques\Tables\MatierePremierePrixHistoriquesTable;
use App\Models\MatierePremierePrixHistorique;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatierePremierePrixHistoriqueResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = MatierePremierePrixHistorique::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MatierePremierePrixHistoriqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatierePremierePrixHistoriquesTable::configure($table);
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
            'index' => ListMatierePremierePrixHistoriques::route('/'),
            'create' => CreateMatierePremierePrixHistorique::route('/create'),
            'edit' => EditMatierePremierePrixHistorique::route('/{record}/edit'),
        ];
    }
}
