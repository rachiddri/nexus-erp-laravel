<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatierePremierePrixHistorique extends Model
{
    use HasFactory;

    protected $table = 'matieres_premieres_prix_historique';

    protected $fillable = [
        'matiere_premiere_id',
        'prix_avant',
        'prix_apres',
        'motif',
        'date_debut',
        'date_fin',
        'utilisateur_id'
    ];

    protected $casts = [
        'prix_avant' => 'decimal:2',
        'prix_apres' => 'decimal:2',
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
    ];

    public function matierePremiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\MatierePremiere::class, 'matiere_premiere_id');
    }

    public function utilisateur(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'utilisateur_id');
    }
}
