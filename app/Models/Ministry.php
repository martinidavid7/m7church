<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Ministry extends Model
{
    protected $table = 'ministries';

    protected $fillable = [
        'name',
        'description',
        'logo',
        'leader_id'
    ];

    public function leader(){
        return $this->belongsTo(User::class, 'leader_id');
    }

}
