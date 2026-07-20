<?php

namespace App\Filament\Resources\Paiements;

use App\Filament\Resources\Paiements\Pages\CreatePaiement;
use App\Filament\Resources\Paiements\Pages\EditPaiement;
use App\Filament\Resources\Paiements\Pages\ListPaiements;
use App\Filament\Resources\Paiements\Schemas\PaiementForm;
use App\Filament\Resources\Paiements\Tables\PaiementsTable;
use App\Models\Paiement;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PaiementResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Paiements';

    protected static ?string $model = Paiement::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return PaiementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PaiementsTable::configure($table);
    }

        public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\Paiements\RelationManagers\PaiementImputationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaiements::route('/'),
            'create' => CreatePaiement::route('/create'),
            'edit' => EditPaiement::route('/{record}/edit'),
        ];
    }
}
