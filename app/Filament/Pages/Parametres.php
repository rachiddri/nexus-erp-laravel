<?php

namespace App\Filament\Pages;

use App\Models\Client;
use App\Models\Depot;
use App\Models\EtapeProduction;
use App\Models\MatierePremiere;
use App\Models\Produit;
use App\Models\User;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class Parametres extends Page
{
    protected static ?string $title = 'Paramètres';

    protected static ?string $navigationLabel = 'Paramètres';

    protected static string|UnitEnum|null $navigationGroup = 'Paramètres';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::Cog6Tooth;

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.parametres';

    public array $stats = [];

    public function mount(): void
    {
        $this->stats = [
            'depots' => Depot::count(),
            'matieres' => MatierePremiere::where('actif', true)->count(),
            'produits' => Produit::where('actif', true)->count(),
            'clients' => Client::where('actif', true)->count(),
            'etapes' => EtapeProduction::count(),
            'users' => User::where('actif', true)->count(),
        ];
    }
}
