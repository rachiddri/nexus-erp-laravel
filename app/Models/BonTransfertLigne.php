<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonTransfertLigne extends Model
{
    use HasFactory;

    protected $table = 'bon_transfert_lignes';

    protected $fillable = [
        'bon_transfert_id',
        'produit_physique_id',
        'code_affiche'
    ];

    public function bonTransfert(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\BonTransfert::class, 'bon_transfert_id');
    }

    public function produitPhysique(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ProduitPhysique::class, 'produit_physique_id');
    }
}
