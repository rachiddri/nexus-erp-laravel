<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Emplacement extends Model
{
    use HasFactory;

    protected $table = 'emplacements';

    protected $fillable = [
        'hangar_id',
        'code_emplacement',
        'emplacement_able_type',
        'emplacement_able_id',
        'zone',
        'capacite_max',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function hangar(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Hangar::class, 'hangar_id');
    }

    public function produitsPhysiques(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\ProduitPhysique::class);
    }
}
