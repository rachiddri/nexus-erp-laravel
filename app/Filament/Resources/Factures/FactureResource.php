<?php

namespace App\Filament\Resources\Factures;

use App\Filament\Resources\Factures\Pages\CreateFacture;
use App\Filament\Resources\Factures\Pages\EditFacture;
use App\Filament\Resources\Factures\Pages\ListFactures;
use App\Filament\Resources\Factures\Schemas\FactureForm;
use App\Filament\Resources\Factures\Tables\FacturesTable;
use App\Models\Facture;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FactureResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Facturation';

    protected static ?string $model = Facture::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FactureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacturesTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Factures\RelationManagers\FactureLignesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFactures::route('/'),
            'create' => CreateFacture::route('/create'),
            'edit' => EditFacture::route('/{record}/edit'),
        ];
    }
}
