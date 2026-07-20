<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FactureLigne extends Model
{
    use HasFactory;

    protected $table = 'facture_lignes';

    protected $fillable = [
        'facture_id',
        'produit_id',
        'designation',
        'quantite',
        'prix_unitaire',
        'montant_total'
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'montant_total' => 'decimal:2',
    ];

    public function facture(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Facture::class, 'facture_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }
}
