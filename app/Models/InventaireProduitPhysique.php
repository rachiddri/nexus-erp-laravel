<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventaireProduitPhysique extends Model
{
    use HasFactory;

    protected $table = 'inventaire_produits_physiques';

    protected $fillable = [
        'inventaire_id',
        'produit_physique_id',
        'statut'
    ];

    public function inventaire(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventaire::class, 'inventaire_id');
    }

    public function produitPhysique(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ProduitPhysique::class, 'produit_physique_id');
    }
}
