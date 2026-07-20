<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventaireLigne extends Model
{
    use HasFactory;

    protected $table = 'inventaire_lignes';

    protected $fillable = [
        'inventaire_id',
        'matiere_premiere_id',
        'quantite_theorique',
        'quantite_reelle',
        'ecart'
    ];

    protected $casts = [
        'quantite_theorique' => 'decimal:2',
        'quantite_reelle' => 'decimal:2',
        'ecart' => 'decimal:2',
    ];

    public function inventaire(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Inventaire::class, 'inventaire_id');
    }

    public function matierePremiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\MatierePremiere::class, 'matiere_premiere_id');
    }
}
