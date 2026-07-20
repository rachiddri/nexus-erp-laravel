<?php

namespace App\Filament\Resources\BonCommandes;

use App\Filament\Resources\BonCommandes\Pages\CreateBonCommande;
use App\Filament\Resources\BonCommandes\Pages\EditBonCommande;
use App\Filament\Resources\BonCommandes\Pages\ListBonCommandes;
use App\Filament\Resources\BonCommandes\Schemas\BonCommandeForm;
use App\Filament\Resources\BonCommandes\Tables\BonCommandesTable;
use App\Models\BonCommande;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BonCommandeResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Commercial';

    protected static ?string $model = BonCommande::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BonCommandeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BonCommandesTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\BonCommandes\RelationManagers\BonCommandeLignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBonCommandes::route('/'),
            'create' => CreateBonCommande::route('/create'),
            'edit' => EditBonCommande::route('/{record}/edit'),
        ];
    }
}
