<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'specialization',
        'phone',
        'email',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    // public function user()
    // {
    //     return $this->hasOne(User::class);
    // }
}
