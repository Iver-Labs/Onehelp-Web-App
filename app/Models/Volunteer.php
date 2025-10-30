<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $primaryKey = 'volunteer_id';
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'phone', 'address',
        'date_of_birth', 'bio', 'profile_image', 'total_hours', 'events_completed'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations() {
        return $this->hasMany(EventRegistration::class, 'volunteer_id');
    }

    public function skills() {
        return $this->belongsToMany(Skill::class, 'volunteer_skills', 'volunteer_id', 'skill_id')
                    ->withPivot('proficiency_level')
                    ->withTimestamps();
    }
}
