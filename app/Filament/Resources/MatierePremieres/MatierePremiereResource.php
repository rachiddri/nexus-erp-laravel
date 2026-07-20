<?php

namespace App\Filament\Resources\MatierePremieres;

use App\Filament\Resources\MatierePremieres\Pages\CreateMatierePremiere;
use App\Filament\Resources\MatierePremieres\Pages\EditMatierePremiere;
use App\Filament\Resources\MatierePremieres\Pages\ListMatierePremieres;
use App\Filament\Resources\MatierePremieres\Schemas\MatierePremiereForm;
use App\Filament\Resources\MatierePremieres\Tables\MatierePremieresTable;
use App\Models\MatierePremiere;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MatierePremiereResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = MatierePremiere::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MatierePremiereForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MatierePremieresTable::configure($table);
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
            'index' => ListMatierePremieres::route('/'),
            'create' => CreateMatierePremiere::route('/create'),
            'edit' => EditMatierePremiere::route('/{record}/edit'),
        ];
    }
}
