<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $table = 'event_registrations';

    protected $primaryKey = 'registration_id';
    protected $fillable = [
        'event_id', 'volunteer_id', 'registered_at', 'status', 'notes',
        'hours_contributed', 'certificate_issued'
    ];

    public function volunteer() {
        return $this->belongsTo(Volunteer::class, 'volunteer_id');
    }

    public function event() {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendance() {
        return $this->hasMany(Attendance::class, 'registration_id');
    }

    public function feedback() {
        return $this->hasOne(Feedback::class, 'registration_id');
    }
}
