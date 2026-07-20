<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonTransfert extends Model
{
    use HasFactory;

    protected $table = 'bons_transfert';

    protected $fillable = [
        'numero',
        'depot_origine_id',
        'depot_destination_id',
        'date_transfert',
        'motif',
        'statut',
        'cree_par',
        'valide_par',
        'valide_le'
    ];

    protected $casts = [
        'date_transfert' => 'date',
        'valide_le' => 'datetime',
    ];

    public function depotDestination(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Depot::class, 'depot_destination_id');
    }

    public function depotOrigine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Depot::class, 'depot_origine_id');
    }

    public function bonTransfertLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BonTransfertLigne::class);
    }
}
