<?php

namespace App\Filament\Resources\ProduitPhysiqueHistoriques;

use App\Filament\Resources\ProduitPhysiqueHistoriques\Pages\CreateProduitPhysiqueHistorique;
use App\Filament\Resources\ProduitPhysiqueHistoriques\Pages\EditProduitPhysiqueHistorique;
use App\Filament\Resources\ProduitPhysiqueHistoriques\Pages\ListProduitPhysiqueHistoriques;
use App\Filament\Resources\ProduitPhysiqueHistoriques\Schemas\ProduitPhysiqueHistoriqueForm;
use App\Filament\Resources\ProduitPhysiqueHistoriques\Tables\ProduitPhysiqueHistoriquesTable;
use App\Models\ProduitPhysiqueHistorique;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ProduitPhysiqueHistoriqueResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;

    protected static string|\UnitEnum|null $navigationGroup = 'Production';

    protected static ?string $model = ProduitPhysiqueHistorique::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProduitPhysiqueHistoriqueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProduitPhysiqueHistoriquesTable::configure($table);
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
            'index' => ListProduitPhysiqueHistoriques::route('/'),
            'create' => CreateProduitPhysiqueHistorique::route('/create'),
            'edit' => EditProduitPhysiqueHistorique::route('/{record}/edit'),
        ];
    }
}
