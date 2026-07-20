<?php

namespace App\Filament\Resources\Avoirs;

use App\Filament\Resources\Avoirs\Pages\CreateAvoir;
use App\Filament\Resources\Avoirs\Pages\EditAvoir;
use App\Filament\Resources\Avoirs\Pages\ListAvoirs;
use App\Filament\Resources\Avoirs\Schemas\AvoirForm;
use App\Filament\Resources\Avoirs\Tables\AvoirsTable;
use App\Models\Avoir;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AvoirResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Facturation';

    protected static ?string $model = Avoir::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AvoirForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AvoirsTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Avoirs\RelationManagers\AvoirLignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAvoirs::route('/'),
            'create' => CreateAvoir::route('/create'),
            'edit' => EditAvoir::route('/{record}/edit'),
        ];
    }
}
