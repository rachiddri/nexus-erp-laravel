<?php

namespace App\Filament\Resources\MouvementStockMatieres;

use App\Filament\Resources\MouvementStockMatieres\Pages\CreateMouvementStockMatiere;
use App\Filament\Resources\MouvementStockMatieres\Pages\EditMouvementStockMatiere;
use App\Filament\Resources\MouvementStockMatieres\Pages\ListMouvementStockMatieres;
use App\Filament\Resources\MouvementStockMatieres\Schemas\MouvementStockMatiereForm;
use App\Filament\Resources\MouvementStockMatieres\Tables\MouvementStockMatieresTable;
use App\Models\MouvementStockMatiere;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MouvementStockMatiereResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = MouvementStockMatiere::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return MouvementStockMatiereForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MouvementStockMatieresTable::configure($table);
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
            'index' => ListMouvementStockMatieres::route('/'),
            'create' => CreateMouvementStockMatiere::route('/create'),
            'edit' => EditMouvementStockMatiere::route('/{record}/edit'),
        ];
    }
}
