<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UF extends Model
{
    protected $table = 'ufs';  // Note: table name should be plural 'ufs' to match migration

    protected $fillable = [
        'uf',
        'name'
    ];

    public function cities(): HasMany
    {
        return $this->hasMany(City::class, 'uf_id');
    }
}
