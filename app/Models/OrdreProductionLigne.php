<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdreProductionLigne extends Model
{
    use HasFactory;

    protected $table = 'ordre_production_lignes';

    protected $fillable = [
        'ordre_production_id',
        'produit_id',
        'quantite',
        'quantite_produite',
        'quantite_rebutee'
    ];

    public function ordreProduction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\OrdreProduction::class, 'ordre_production_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }

    public function lotProduits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\LotProduit::class);
    }
}
