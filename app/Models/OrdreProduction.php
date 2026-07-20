<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrdreProduction extends Model
{
    use HasFactory;

    protected $table = 'ordres_production';

    protected $fillable = [
        'numero_op',
        'bon_commande_id',
        'depot_matiere_id',
        'date_lancement',
        'date_prevue_fin',
        'statut',
        'priorite',
        'origine',
        'notes',
        'valide_par',
        'valide_le',
        'cree_par'
    ];

    protected $casts = [
        'date_lancement' => 'date',
        'date_prevue_fin' => 'date',
        'valide_le' => 'datetime',
    ];

    public function bonCommande(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\BonCommande::class, 'bon_commande_id');
    }

    public function depotMatiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Depot::class, 'depot_matiere_id');
    }

    public function ordreProductionLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\OrdreProductionLigne::class);
    }

    public function lots(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Lot::class);
    }
}
