<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produit extends Model
{
    use HasFactory;

    protected $table = 'produits';

    protected $fillable = [
        'nom',
        'reference',
        'description',
        'categorie',
        'prix_vente',
        'tva_taux',
        'stock_alerte_min',
        'gamme',
        'longueur',
        'largeur',
        'hauteur',
        'poids',
        'image_principale',
        'fiches_techniques',
        'notes_production',
        'actif'
    ];

    protected $casts = [
        'prix_vente' => 'decimal:2',
        'tva_taux' => 'decimal:2',
        'longueur' => 'decimal:2',
        'largeur' => 'decimal:2',
        'hauteur' => 'decimal:2',
        'poids' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function lotProduits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\LotProduit::class);
    }

    public function produitEtapes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitEtape::class);
    }

    public function produitMatierePremieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitMatierePremiere::class);
    }

    public function bonCommandeLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BonCommandeLigne::class);
    }

    public function ordreProductionLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\OrdreProductionLigne::class);
    }

    public function produitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitPhysique::class);
    }

    public function factureLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\FactureLigne::class);
    }

    public function avoirLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AvoirLigne::class);
    }

    public function retourClientLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RetourClientLigne::class);
    }
}
