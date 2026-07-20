<?php

namespace App\Filament\Resources\InventaireProduitPhysiques;

use App\Filament\Resources\InventaireProduitPhysiques\Pages\CreateInventaireProduitPhysique;
use App\Filament\Resources\InventaireProduitPhysiques\Pages\EditInventaireProduitPhysique;
use App\Filament\Resources\InventaireProduitPhysiques\Pages\ListInventaireProduitPhysiques;
use App\Filament\Resources\InventaireProduitPhysiques\Schemas\InventaireProduitPhysiqueForm;
use App\Filament\Resources\InventaireProduitPhysiques\Tables\InventaireProduitPhysiquesTable;
use App\Models\InventaireProduitPhysique;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventaireProduitPhysiqueResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Stock';

    protected static ?string $model = InventaireProduitPhysique::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return InventaireProduitPhysiqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventaireProduitPhysiquesTable::configure($table);
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
            'index' => ListInventaireProduitPhysiques::route('/'),
            'create' => CreateInventaireProduitPhysique::route('/create'),
            'edit' => EditInventaireProduitPhysique::route('/{record}/edit'),
        ];
    }
}
