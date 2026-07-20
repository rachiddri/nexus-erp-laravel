<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MouvementStockMatiere extends Model
{
    use HasFactory;

    protected $table = 'mouvements_stock_matiere';

    protected $fillable = [
        'matiere_premiere_id',
        'depot_id',
        'type_mouvement',
        'quantite',
        'cout_unitaire',
        'reference',
        'document_lie',
        'cree_par',
        'notes'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'cout_unitaire' => 'decimal:2',
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
