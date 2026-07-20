<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DefautsProduction extends Model
{
    use HasFactory;

    protected $table = 'defauts_production';

    protected $fillable = [
        'lot_produit_id',
        'etape_production_id',
        'type_defaut',
        'description',
        'gravite',
        'quantite_impactee',
        'cause_racine',
        'action_immediate',
        'decision',
        'statut',
        'signale_par',
        'resolu_par',
        'date_resolution'
    ];

    protected $casts = [
        'date_resolution' => 'datetime',
    ];

    public function etapeProduction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\EtapeProduction::class, 'etape_production_id');
    }

    public function lotProduit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\LotProduit::class, 'lot_produit_id');
    }
}
