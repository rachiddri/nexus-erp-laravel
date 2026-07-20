<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lot extends Model
{
    use HasFactory;

    protected $table = 'lots';

    protected $fillable = [
        'numero_lot',
        'ordre_production_id',
        'statut',
        'date_ouverture',
        'date_cloture',
        'notes'
    ];

    protected $casts = [
        'date_ouverture' => 'datetime',
        'date_cloture' => 'datetime',
    ];

    public function ordreProduction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\OrdreProduction::class, 'ordre_production_id');
    }

    public function lotProduits(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\LotProduit::class);
    }

    public function produitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitPhysique::class);
    }
}
