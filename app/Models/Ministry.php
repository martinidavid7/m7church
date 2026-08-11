<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    protected $table = 'ministries';

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    public function people()
    {
        return $this->belongsToMany(Person::class, 'person_ministry')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function leaders()
    {
        return $this->people()->wherePivot('role', 'lider');
    }

    public function members()
    {
        return $this->people()->wherePivot('role', 'membro');
    }
}
