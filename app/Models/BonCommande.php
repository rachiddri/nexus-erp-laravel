<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonCommande extends Model
{
    use HasFactory;

    protected $table = 'bons_commande';

    protected $fillable = [
        'numero_bc',
        'client_id',
        'date_commande',
        'date_livraison_souhaitee',
        'adresse_livraison',
        'statut',
        'montant_total',
        'montant_ht',
        'montant_ttc',
        'remise_globale',
        'notes',
        'approuve_prix_plancher_par',
        'approuve_prix_plancher_le',
        'cree_par'
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_livraison_souhaitee' => 'date',
        'montant_total' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'remise_globale' => 'decimal:2',
        'approuve_prix_plancher_le' => 'datetime',
    ];

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function bonCommandeLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BonCommandeLigne::class);
    }

    public function factures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Facture::class);
    }

    public function ordresProductions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\OrdreProduction::class);
    }

    public function documentsSorties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DocumentsSortie::class);
    }
}
