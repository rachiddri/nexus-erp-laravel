<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotProduit extends Model
{
    use HasFactory;

    protected $table = 'lot_produits';

    protected $fillable = [
        'lot_id',
        'ordre_production_ligne_id',
        'produit_id',
        'quantite_theorique',
        'quantite_produite',
        'quantite_rebutee'
    ];

    public function lot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Lot::class, 'lot_id');
    }

    public function ordreProductionLigne(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\OrdreProductionLigne::class, 'ordre_production_ligne_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }

    public function produitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitPhysique::class);
    }

    public function lotConsommationMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\LotConsommationMatiere::class);
    }

    public function defautsProductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DefautsProduction::class);
    }
}
