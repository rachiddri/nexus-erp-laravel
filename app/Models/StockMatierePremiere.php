<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StockMatierePremiere extends Model
{
    use HasFactory;

    protected $table = 'stock_matieres_premieres';

    protected $fillable = [
        'matiere_premiere_id',
        'depot_id',
        'quantite_disponible',
        'quantite_reservee'
    ];

    protected $casts = [
        'quantite_disponible' => 'decimal:2',
        'quantite_reservee' => 'decimal:2',
    ];

    public function depot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Depot::class, 'depot_id');
    }

    public function matierePremiere(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\MatierePremiere::class, 'matiere_premiere_id');
    }
}
