<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Depot extends Model
{
    use HasFactory;

    protected $table = 'depots';

    protected $fillable = [
        'nom',
        'code',
        'type',
        'adresse',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function hangars(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Hangar::class);
    }

    public function mouvementsStockMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\MouvementStockMatiere::class);
    }

    public function stockMatieresPremieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\StockMatierePremiere::class);
    }

    public function ordresProductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\OrdreProduction::class);
    }

    public function inventaires(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Inventaire::class);
    }

    public function bonsTransferts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BonTransfert::class);
    }
}
