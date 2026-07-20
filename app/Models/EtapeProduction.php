<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EtapeProduction extends Model
{
    use HasFactory;

    protected $table = 'etapes_production';

    protected $fillable = [
        'nom',
        'ordre',
        'description',
        'type_controle',
        'seuil_conformite',
        'actif'
    ];

    protected $casts = [
        'seuil_conformite' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function produitEtapes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitEtape::class);
    }

    public function produitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitPhysique::class);
    }

    public function defautsProductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DefautsProduction::class);
    }
}
