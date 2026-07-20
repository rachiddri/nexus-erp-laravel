<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AvoirLigne extends Model
{
    use HasFactory;

    protected $table = 'avoir_lignes';

    protected $fillable = [
        'avoir_id',
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

    public function avoir(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Avoir::class, 'avoir_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }
}
