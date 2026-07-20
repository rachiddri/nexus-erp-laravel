<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RetourClient extends Model
{
    use HasFactory;

    protected $table = 'retours_clients';

    protected $fillable = [
        'client_id',
        'date_retour',
        'motif_global',
        'decision',
        'statut',
        'document_sortie_id',
        'motif_rejet',
        'cree_par',
        'traite_par',
        'traite_le'
    ];

    protected $casts = [
        'date_retour' => 'date',
        'traite_le' => 'datetime',
    ];

    public function client(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function documentSortie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\DocumentsSortie::class, 'document_sortie_id');
    }

    public function retourClientLignes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\RetourClientLigne::class);
    }
}
