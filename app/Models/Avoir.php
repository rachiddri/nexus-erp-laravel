<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Avoir extends Model
{
    use HasFactory;

    protected $table = 'avoirs';

    protected $fillable = [
        'numero_avoir',
        'facture_id',
        'client_id',
        'date_avoir',
        'motif',
        'taux_tva',
        'montant_ht',
        'montant_tva',
        'montant_ttc',
        'montant_ttc_lettres',
        'statut',
        'valide_par'
    ];

    protected $attributes = [
        'taux_tva' => 19.00,
    ];

    protected $casts = [
        'date_avoir' => 'date',
        'taux_tva' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function facture(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Facture::class, 'facture_id');
    }

    public function avoirLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AvoirLigne::class);
    }
}
