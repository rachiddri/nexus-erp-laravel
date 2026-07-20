<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaiementImputation extends Model
{
    use HasFactory;

    protected $table = 'paiement_imputations';

    protected $fillable = [
        'paiement_id',
        'facture_id',
        'montant'
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];

    public function facture(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Facture::class, 'facture_id');
    }

    public function paiement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Paiement::class, 'paiement_id');
    }
}
