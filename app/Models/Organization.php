<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $primaryKey = 'organization_id';

    protected $fillable = [
        'user_id',
        'org_name',
        'org_type',
        'founded_year',        // NEW
        'registration_number',
        'contact_person',
        'phone',
        'address',
        'description',
        'rating',              // NEW
        'logo_image',
        'is_verified',
        'verified_at'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
        'founded_year' => 'integer',
        'rating' => 'decimal:2'
    ];

    /**
     * Get the user that owns the organization
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Get events for this organization
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'organization_id', 'organization_id');
    }

    /**
     * Get all event registrations for this organization's events
     */
    public function eventRegistrations()
    {
        return $this->hasManyThrough(
            EventRegistration::class,
            Event::class,
            'organization_id',    // Foreign key on events table
            'event_id',           // Foreign key on event_registrations table
            'organization_id',    // Local key on organizations table
            'event_id'            // Local key on events table
        );
    }

    /**
     * Get pending applications count
     */
    public function pendingApplicationsCount()
    {
        return $this->eventRegistrations()->where('status', 'pending')->count();
    }
}