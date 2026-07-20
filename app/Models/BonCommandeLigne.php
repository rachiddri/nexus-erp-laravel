<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonCommandeLigne extends Model
{
    use HasFactory;

    protected $table = 'bon_commande_lignes';

    protected $fillable = [
        'bon_commande_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'montant_total',
        'description'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
    ];

    public function bonCommande(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\BonCommande::class, 'bon_commande_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }
}
