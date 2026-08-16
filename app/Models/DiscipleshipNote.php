<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscipleshipNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'discipleship_id',
        'author_id',
        'body',
    ];

    public function discipleship()
    {
        return $this->belongsTo(Discipleship::class);
    }

    public function author()
    {
        return $this->belongsTo(Person::class, 'author_id');
    }
}
