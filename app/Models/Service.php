<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Service extends Model
{
    use SoftDeletes;

    public const DAYS_OF_WEEK = [
        'Domingo',
        'Segunda-feira',
        'Terça-feira',
        'Quarta-feira',
        'Quinta-feira',
        'Sexta-feira',
        'Sábado',
    ];

    protected $fillable = ['service', 'day_of_week', 'time', 'service_type_id'];

    public function serviceType(){
        return $this->belongsTo(ServiceType::class);
    }

    public static function timeOptions(): array
    {
        $options = [];

        for ($minutes = 0; $minutes < 24 * 60; $minutes += 30) {
            $options[] = sprintf('%02d:%02d', intdiv($minutes, 60), $minutes % 60);
        }

        return $options;
    }
}
