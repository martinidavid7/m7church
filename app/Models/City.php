<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class City extends Model
{
    protected $fillable = [
        'name',
        'uf_id',
    ];

    public function uf(): BelongsTo
    {
        return $this->belongsTo(UF::class, 'uf_id');
    }
}
