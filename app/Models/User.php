<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'email', 
        'password_hash', 
        'user_type', 
        'created_at', 
        'last_login', 
        'is_active'
    ];

    protected $hidden = [
        'password_hash',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'last_login' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    // Override the default password column name
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // Relationships
    public function volunteer() {
        return $this->hasOne(Volunteer::class, 'user_id', 'user_id');
    }

    public function organization() {
        return $this->hasOne(Organization::class, 'user_id', 'user_id');
    }

    public function notifications() {
        return $this->hasMany(Notification::class, 'user_id', 'user_id');
    }

    // Helper methods
    public function isVolunteer()
    {
        return $this->user_type === 'volunteer';
    }

    public function isOrganization()
    {
        return $this->user_type === 'organization';
    }

    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    // Update last login timestamp
    public function updateLastLogin()
    {
        $this->last_login = now();
        $this->save();
    }
}