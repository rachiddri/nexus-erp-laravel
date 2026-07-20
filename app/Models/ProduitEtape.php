<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProduitEtape extends Model
{
    use HasFactory;

    protected $table = 'produit_etapes';

    protected $fillable = [
        'produit_id',
        'etape_production_id',
        'ordre',
        'duree_minutes',
        'instructions'
    ];

    public function etapeProduction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\EtapeProduction::class, 'etape_production_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }
}
