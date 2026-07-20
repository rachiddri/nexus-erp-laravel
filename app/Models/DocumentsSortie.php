<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentsSortie extends Model
{
    use HasFactory;

    protected $table = 'documents_sortie';

    protected $fillable = [
        'numero',
        'type',
        'client_id',
        'bon_commande_id',
        'date_sortie',
        'adresse_livraison',
        'statut',
        'valide_par',
        'valide_le',
        'cree_par'
    ];

    protected $casts = [
        'date_sortie' => 'date',
        'valide_le' => 'datetime',
    ];

    public function bonCommande(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\BonCommande::class, 'bon_commande_id');
    }

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function documentSortieLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\DocumentSortieLigne::class, 'document_sortie_id');
    }

    public function retoursClients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RetourClient::class);
    }
}
