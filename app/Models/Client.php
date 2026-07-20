<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $fillable = [
        'raison_sociale',
        'nif',
        'email',
        'tel',
        'adresse',
        'plafond_credit',
        'solde',
        'notes',
        'actif'
    ];

    protected $casts = [
        'plafond_credit' => 'decimal:2',
        'solde' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function bonsCommandes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\BonCommande::class);
    }

    public function factures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Facture::class);
    }

    public function avoirs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Avoir::class);
    }

    public function documentsSorties(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DocumentsSortie::class);
    }

    public function paiements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Paiement::class);
    }

    public function mouvementsSoldeClients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\MouvementSoldeClient::class);
    }

    public function retoursClients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RetourClient::class);
    }
}
