<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'factures';

    protected $fillable = [
        'numero_facture',
        'client_id',
        'bon_commande_id',
        'date_facture',
        'date_echeance',
        'mode_reglement',
        'taux_tva',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'montant_ttc_lettres',
        'montant_paye',
        'remise',
        'statut',
        'notes',
        'emise_par',
        'emise_le'
    ];

    protected $attributes = [
        'taux_tva' => 19.00,
    ];

    protected $casts = [
        'date_facture' => 'date',
        'date_echeance' => 'date',
        'taux_tva' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'remise' => 'decimal:2',
        'emise_le' => 'datetime',
    ];

    public function bonCommande(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\BonCommande::class, 'bon_commande_id');
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function avoirs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Avoir::class);
    }

    public function factureLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\FactureLigne::class);
    }

    public function paiementImputations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\PaiementImputation::class);
    }
}
