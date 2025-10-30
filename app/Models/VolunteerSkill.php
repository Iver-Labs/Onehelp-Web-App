<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    use HasFactory;

    protected $primaryKey = 'volunteer_id';
    
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'address',
        'date_of_birth',
        'bio',
        'profile_image',
        'total_hours',
        'events_completed'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'total_hours' => 'integer',
        'events_completed' => 'integer',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function skills() {
        return $this->belongsToMany(
            Skill::class, 
            'volunteer_skills', 
            'volunteer_id', 
            'skill_id'
        );
    }

    public function eventRegistrations() {
        return $this->hasMany(EventRegistration::class, 'volunteer_id', 'volunteer_id');
    }

    public function attendances() {
        return $this->hasMany(Attendance::class, 'volunteer_id', 'volunteer_id');
    }

    public function feedbacks() {
        return $this->hasMany(Feedback::class, 'volunteer_id', 'volunteer_id');
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function incrementEventCompletion()
    {
        $this->increment('events_completed');
    }

    public function addHours($hours)
    {
        $this->increment('total_hours', $hours);
    }
}