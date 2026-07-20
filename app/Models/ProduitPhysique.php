<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProduitPhysique extends Model
{
    use HasFactory;

    protected $table = 'produits_physiques';

    protected $fillable = [
        'code_affiche',
        'produit_id',
        'lot_id',
        'lot_produit_id',
        'etape_actuelle_id',
        'emplacement_id',
        'statut',
        'date_creation',
        'date_sortie',
        'cree_par'
    ];

    protected $casts = [
        'date_creation' => 'datetime',
        'date_sortie' => 'datetime',
    ];

    public function emplacement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Emplacement::class, 'emplacement_id');
    }

    public function etapeActuelle(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\EtapeProduction::class, 'etape_actuelle_id');
    }

    public function lot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Lot::class, 'lot_id');
    }

    public function lotProduit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\LotProduit::class, 'lot_produit_id');
    }

    public function produit(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Produit::class, 'produit_id');
    }

    public function produitPhysiqueHistoriques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitPhysiqueHistorique::class);
    }

    public function documentSortieLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DocumentSortieLigne::class);
    }

    public function retourClientLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RetourClientLigne::class);
    }

    public function bonTransfertLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BonTransfertLigne::class);
    }

    public function inventaireProduitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\InventaireProduitPhysique::class);
    }
}
