<?php

namespace App\Filament\Resources\LotConsommationMatieres;

use App\Filament\Resources\LotConsommationMatieres\Pages\CreateLotConsommationMatiere;
use App\Filament\Resources\LotConsommationMatieres\Pages\EditLotConsommationMatiere;
use App\Filament\Resources\LotConsommationMatieres\Pages\ListLotConsommationMatieres;
use App\Filament\Resources\LotConsommationMatieres\Schemas\LotConsommationMatiereForm;
use App\Filament\Resources\LotConsommationMatieres\Tables\LotConsommationMatieresTable;
use App\Models\LotConsommationMatiere;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LotConsommationMatiereResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = LotConsommationMatiere::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return LotConsommationMatiereForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LotConsommationMatieresTable::configure($table);
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
            'index' => ListLotConsommationMatieres::route('/'),
            'create' => CreateLotConsommationMatiere::route('/create'),
            'edit' => EditLotConsommationMatiere::route('/{record}/edit'),
        ];
    }
}
