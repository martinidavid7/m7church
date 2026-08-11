<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'ministry_id',
        'service_id',
        'title',
        'date',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Nome exibido da escala: o da reunião vinculada, ou o título livre quando avulsa.
     */
    public function getDisplayTitleAttribute(): string
    {
        return $this->service?->service ?? $this->title ?? 'Escala';
    }

    public function assignments()
    {
        return $this->hasMany(ScheduleAssignment::class);
    }

    public function people()
    {
        return $this->belongsToMany(Person::class, 'schedule_assignments')
            ->withPivot('function')
            ->withTimestamps();
    }
}
