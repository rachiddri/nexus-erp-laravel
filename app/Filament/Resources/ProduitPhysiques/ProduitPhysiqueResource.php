<?php

namespace App\Filament\Resources\ProduitPhysiques;

use App\Filament\Resources\ProduitPhysiques\Pages\CreateProduitPhysique;
use App\Filament\Resources\ProduitPhysiques\Pages\EditProduitPhysique;
use App\Filament\Resources\ProduitPhysiques\Pages\ListProduitPhysiques;
use App\Filament\Resources\ProduitPhysiques\Schemas\ProduitPhysiqueForm;
use App\Filament\Resources\ProduitPhysiques\Tables\ProduitPhysiquesTable;
use App\Models\ProduitPhysique;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProduitPhysiqueResource extends Resource
{
    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = ProduitPhysique::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProduitPhysiqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduitPhysiquesTable::configure($table);
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
            'index' => ListProduitPhysiques::route('/'),
            'create' => CreateProduitPhysique::route('/create'),
            'edit' => EditProduitPhysique::route('/{record}/edit'),
        ];
    }
}
