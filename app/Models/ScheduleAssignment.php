<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleAssignment extends Model
{
    protected $fillable = [
        'schedule_id',
        'person_id',
        'function',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}
