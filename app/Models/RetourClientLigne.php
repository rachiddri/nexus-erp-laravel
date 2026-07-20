<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RetourClientLigne extends Model
{
    use HasFactory;

    protected $table = 'retour_client_lignes';

    protected $fillable = [
        'retour_client_id',
        'produit_physique_id',
        'produit_id',
        'quantite',
        'motif',
        'etat_produit'
    ];

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }

    public function produitPhysique(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ProduitPhysique::class, 'produit_physique_id');
    }

    public function retourClient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\RetourClient::class, 'retour_client_id');
    }
}
