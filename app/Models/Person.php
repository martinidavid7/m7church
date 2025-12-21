<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;



class Person extends Model
{

    protected $table = 'persons';
    protected $fillable = [
        'name',
        'birth_date',
        'gender',
        'marital_status',
        'address',
        'number',
        'neighborhood',
        'complement',
        'zip_code',
        'landline_phone',
        'mobile_phone',
        'mail',
        'profession',
        'education_level',
        'photo',
        'baptism_date',
        'membership_date',
        'active',
        'observations',
        'user_id',
        'city_id'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'baptism_date' => 'date',
        'membership_date' => 'datetime',
    ];


    public function city(){
        return $this->belongsTo(City::class, 'city_id');
    }

    // Family relationships
    public function familyRelationships(){
        return $this->hasMany(FamilyRelationship::class, 'person_id');
    }

    public function relatedTo(){
        return $this->hasMany(FamilyRelationship::class, 'related_person_id');
    }

    // Get spouse
    public function spouse(){
        return $this->familyRelationships()
            ->where('relationship_type', 'spouse')
            ->with('relatedPerson')
            ->first();
    }

    // Get children
    public function children(){
        return $this->familyRelationships()
            ->where('relationship_type', 'child')
            ->with('relatedPerson')
            ->get();
    }

    // Get parents
    public function parents(){
        return $this->familyRelationships()
            ->where('relationship_type', 'parent')
            ->with('relatedPerson')
            ->get();
    }
}
