<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
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

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function hasRole(String $roleName): bool
    {
        return in_array(strtoupper($roleName), $this->roles->pluck('name')->map(function ($name) {
            return strtoupper($name);
        })->toArray());
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Administrator');
    }

    public function isDoctor(): bool
    {
        return $this->hasRole('Doctor');
    }

    public function isPatient(): bool
    {
        return $this->hasRole('Patient');
    }

    public function doctorProfile()
    {
        return $this->hasOne(Doctor::class);
    }

    public function patientProfile()
    {
        return $this->hasOne(Patient::class);
    }

    public function profile()
    {
        if($this->isAdmin()) {
            return null;
        } elseif($this->isDoctor()) {
            return $this->doctorProfile();
        } else {
            return $this->patientProfile();
        }
    }
}
