<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyRelationship extends Model
{
    protected $table = 'family_relationships';

    protected $fillable = [
        'person_id',
        'related_person_id',
        'relationship_type'
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    public function relatedPerson()
    {
        return $this->belongsTo(Person::class, 'related_person_id');
    }

    /**
     * Scope to get relationships of a specific type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('relationship_type', $type);
    }

    /**
     * Automatically create reciprocal relationship for spouse
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($relationship) {
            if ($relationship->relationship_type === 'spouse') {
                // Create reciprocal relationship
                static::firstOrCreate([
                    'person_id' => $relationship->related_person_id,
                    'related_person_id' => $relationship->person_id,
                    'relationship_type' => 'spouse'
                ]);
            }
        });

        static::deleted(function ($relationship) {
            if ($relationship->relationship_type === 'spouse') {
                // Delete reciprocal relationship
                static::where('person_id', $relationship->related_person_id)
                    ->where('related_person_id', $relationship->person_id)
                    ->where('relationship_type', 'spouse')
                    ->delete();
            }
        });
    }
}
