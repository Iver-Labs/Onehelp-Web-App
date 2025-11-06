<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_id';
    protected $fillable = [
        'organization_id', 'event_name', 'description', 'long_description', 'category',
        'event_date', 'start_time', 'end_time', 'location',
        'max_volunteers', 'registered_count', 'status'
    ];

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function registrations() {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    public function skills() {
        return $this->belongsToMany(Skill::class, 'event_skills', 'event_id', 'skill_id')
                    ->withPivot('is_required')
                    ->withTimestamps();
    }

    public function images() {
        return $this->hasMany(EventImage::class, 'event_id');
    }
}
