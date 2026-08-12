<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipleship extends Model
{
    protected $fillable = [
        'discipulador_id',
        'discipulado_type',
        'discipulado_id',
        'status',
        'started_at',
        'ended_at',
        'notes',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    public function discipulador()
    {
        return $this->belongsTo(Person::class, 'discipulador_id');
    }

    public function discipulado()
    {
        return $this->morphTo();
    }

    public function notesHistory()
    {
        return $this->hasMany(DiscipleshipNote::class)->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeEnded($query)
    {
        return $query->where('status', 'ended');
    }
}
