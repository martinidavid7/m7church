<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\Person;
use App\Models\ChurchType;

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
        'logo',
        'church_type_id',
        'parent_church_id'
    ];


    public function city(){
         return $this->belongsTo(City::class, 'city_id');
    }

    public function pastor(){
         return $this->belongsTo(Person::class, 'pastor_id');
    }

    public function churchType(){
         return $this->belongsTo(ChurchType::class, 'church_type_id');
    }

    // Igreja pai (Matriz ou Igreja Filha)
    public function parentChurch(){
         return $this->belongsTo(Church::class, 'parent_church_id');
    }

    // Igrejas filhas e congregações
    public function childrenChurches(){
         return $this->hasMany(Church::class, 'parent_church_id');
    }
}
