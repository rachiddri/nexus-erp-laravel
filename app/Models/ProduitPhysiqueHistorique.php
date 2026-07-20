<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProduitPhysiqueHistorique extends Model
{
    use HasFactory;

    protected $table = 'produit_physique_historique';

    protected $fillable = [
        'produit_physique_id',
        'type_mouvement',
        'etape_origine',
        'etape_destination',
        'emplacement_origine',
        'emplacement_destination',
        'utilisateur_id',
        'notes',
        'date_mouvement'
    ];

    protected $casts = [
        'date_mouvement' => 'datetime',
    ];

    public function produitPhysique(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ProduitPhysique::class, 'produit_physique_id');
    }

    public function utilisateur(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'utilisateur_id');
    }
}
