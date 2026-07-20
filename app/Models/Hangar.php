<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hangar extends Model
{
    use HasFactory;

    protected $table = 'hangars';

    protected $fillable = [
        'depot_id',
        'nom',
        'responsable_id',
        'actif'
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];

    public function depot(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\Depot::class, 'depot_id');
    }

    public function responsable(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'responsable_id');
    }

    public function emplacements(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Emplacement::class);
    }
}
