<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LotConsommationMatiere extends Model
{
    use HasFactory;

    protected $table = 'lot_consommation_matiere';

    protected $fillable = [
        'lot_produit_id',
        'matiere_premiere_id',
        'quantite_consommee',
        'quantite_rebutee'
    ];

    protected $casts = [
        'quantite_consommee' => 'decimal:2',
        'quantite_rebutee' => 'decimal:2',
    ];

    public function lotProduit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\LotProduit::class, 'lot_produit_id');
    }

    public function matierePremiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\MatierePremiere::class, 'matiere_premiere_id');
    }
}
