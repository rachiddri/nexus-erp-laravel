<?php

namespace App\Filament\Pages;

use App\Enums\StatutProduitPhysique;
use App\Models\ProduitPhysique;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ScanStation extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-qr-code';

    protected static string|\UnitEnum|null $navigationGroup = 'Scan';

    protected static ?string $title = 'Poste de scan';

    protected string $view = 'filament.pages.scan-station';

    public ?string $code = null;

    public $result = null;

    /** @var Collection<int, array<string, mixed>>|null */
    public ?Collection $stockParLieu = null;

    public function mount(): void
    {
        $this->code = request()->query('code');
        if ($this->code) {
            $this->lookup();
        }
    }

    public function lookup(): void
    {
        $code = trim((string) $this->code);
        if ($code === '') {
            $this->result = null;
            $this->stockParLieu = null;

            return;
        }

        /** @var ProduitPhysique|null $pp */
        $pp = ProduitPhysique::with([
            'produit',
            'emplacement',
            'etapeActuelle.hangar',
            'lotProduit.lot.ordreProduction.bonCommande',
        ])->where('code_affiche', $code)->first();

        $this->result = $pp;

        if ($pp && $pp->produit_id) {
            $this->stockParLieu = ProduitPhysique::with(['emplacement', 'etapeActuelle'])
                ->where('produit_id', $pp->produit_id)
                ->get()
                ->groupBy(fn ($x) => $x->emplacement?->nom ?? $x->etapeActuelle?->nom ?? '—')
                ->map(function (Collection $g) {
                    return [
                        'lieu' => $g->first()->emplacement?->nom ?? $g->first()->etapeActuelle?->nom ?? '—',
                        'total' => $g->count(),
                        'en_production' => $g->where('statut', StatutProduitPhysique::EnProduction)->count(),
                        'disponible' => $g->where('statut', StatutProduitPhysique::Disponible)->count(),
                        'livre' => $g->where('statut', StatutProduitPhysique::Livre)->count(),
                        'defaut' => $g->where('statut', StatutProduitPhysique::Defectueux)->count(),
                    ];
                })
                ->values();
        } else {
            $this->stockParLieu = null;
        }
    }
}
