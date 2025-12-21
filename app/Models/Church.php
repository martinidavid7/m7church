<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\Person;

class Church extends Model
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'churches';
    protected $fillable = [
        'church_name',
        'address',
        'number',
        'neighborhood',
        'complement',
        'zip_code',
        'city_id',
        'church_phone',
        'church_mail',
        'pastor_id',
        'logo'
    ];


    public function city(){
         return $this->belongsTo(City::class, 'city_id');
    }

    public function pastor(){
         return $this->belongsTo(Person::class, 'pastor_id');
    }
}
