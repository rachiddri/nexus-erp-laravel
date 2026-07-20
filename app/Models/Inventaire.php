<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventaire extends Model
{
    use HasFactory;

    protected $table = 'inventaires';

    protected $fillable = [
        'numero',
        'type',
        'depot_id',
        'date_inventaire',
        'statut',
        'notes',
        'cree_par',
        'valide_par',
        'date_validation'
    ];

    protected $casts = [
        'date_inventaire' => 'date',
        'date_validation' => 'datetime',
    ];

    public function depot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Depot::class, 'depot_id');
    }

    public function inventaireLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InventaireLigne::class);
    }

    public function inventaireProduitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InventaireProduitPhysique::class);
    }
}
