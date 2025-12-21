<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'name',
        'visit_date',
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
        'profession',
        'education_level',
        'city_id',
        'user_id',
        'mail',
        'accept_receive_messages',
        'observations',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'birth_date' => 'date',
        'accept_receive_messages' => 'boolean',
    ];

    /**
     * Relacionamento com City
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
