<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sequence extends Model
{
    use HasFactory;

    protected $table = 'sequences';

    protected $fillable = [
        'prefixe',
        'annee',
        'dernier_numero'
    ];
}
