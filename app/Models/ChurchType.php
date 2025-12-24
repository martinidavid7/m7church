<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChurchType extends Model
{
    protected $table = 'church_types';

    protected $fillable = [
        'church_type'
    ];

    public function churches(){
        return $this->hasMany(Church::class, 'church_type_id');
    }
}
