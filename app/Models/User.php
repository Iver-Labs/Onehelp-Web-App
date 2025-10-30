<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'email', 'password_hash', 'user_type', 'created_at', 'last_login', 'is_active'
    ];

    // Relationships
    public function volunteer() {
        return $this->hasOne(Volunteer::class, 'user_id');
    }

    public function organization() {
        return $this->hasOne(Organization::class, 'user_id');
    }

    public function notifications() {
        return $this->hasMany(Notification::class, 'user_id');
    }
}
