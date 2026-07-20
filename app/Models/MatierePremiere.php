<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatierePremiere extends Model
{
    use HasFactory;

    protected $table = 'matieres_premieres';

    protected $fillable = [
        'nom',
        'code',
        'description',
        'unite',
        'cout_unitaire_moyen',
        'cout_unitaire_actuel',
        'stock_alerte_min',
        'fiche_technique',
        'actif'
    ];

    protected $casts = [
        'cout_unitaire_moyen' => 'decimal:2',
        'cout_unitaire_actuel' => 'decimal:2',
        'stock_alerte_min' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function mouvementsStockMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\MouvementStockMatiere::class);
    }

    public function stockMatieresPremieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\StockMatierePremiere::class);
    }

    public function matieresPremieresPrixHistoriques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\MatierePremierePrixHistorique::class);
    }

    public function produitMatierePremieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitMatierePremiere::class);
    }

    public function lotConsommationMatieres(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\LotConsommationMatiere::class);
    }

    public function inventaireLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InventaireLigne::class);
    }
}
