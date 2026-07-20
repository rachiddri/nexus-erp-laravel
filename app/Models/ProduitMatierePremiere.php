<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProduitMatierePremiere extends Model
{
    use HasFactory;

    protected $table = 'produit_matiere_premiere';

    protected $fillable = [
        'produit_id',
        'matiere_premiere_id',
        'quantite',
        'rebut'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'rebut' => 'decimal:2',
    ];

    public function matierePremiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\MatierePremiere::class, 'matiere_premiere_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }
}
