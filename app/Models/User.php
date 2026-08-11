<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Lab404\Impersonate\Models\Impersonate;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Impersonate;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    /**
     * Determina se o usuário pode fazer impersonate de outros
     * Apenas admins podem impersonate
     */
    public function canImpersonate(): bool
    {
        return $this->hasRole('Admin');
    }

    /**
     * Determina se este usuário pode ser impersonated
     * Admins não podem ser impersonados
     */
    public function canBeImpersonated(): bool
    {
        return !$this->hasRole('Admin');
    }

    public function person()
    {
        return $this->hasOne(Person::class, 'user_id');
    }

    /**
     * Ministérios vinculados à pessoa deste usuário, com o papel (líder/membro).
     */
    public function ministries()
    {
        return $this->person
            ? $this->person->ministries()->get()
            : collect();
    }
}
