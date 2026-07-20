<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentSortieLigne extends Model
{
    use HasFactory;

    protected $table = 'document_sortie_lignes';

    protected $fillable = [
        'document_sortie_id',
        'produit_physique_id',
        'numero_lot'
    ];

    public function documentSortie(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\DocumentsSortie::class, 'document_sortie_id');
    }

    public function produitPhysique(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\ProduitPhysique::class, 'produit_physique_id');
    }
}
