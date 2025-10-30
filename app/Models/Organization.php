<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $primaryKey = 'organization_id';
    protected $fillable = [
        'user_id', 'org_name', 'org_type', 'registration_number', 'contact_person',
        'phone', 'address', 'description', 'logo_image', 'is_verified', 'verified_at'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function events() {
        return $this->hasMany(Event::class, 'organization_id');
    }

    public function verifications() {
        return $this->hasMany(OrganizationVerification::class, 'organization_id');
    }
}
