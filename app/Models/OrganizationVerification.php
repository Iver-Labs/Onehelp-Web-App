<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationVerification extends Model
{
    use HasFactory;

    protected $primaryKey = 'verification_id';
    protected $fillable = [
        'organization_id', 'document_type', 'document_url', 'status',
        'admin_notes', 'submitted_at', 'reviewed_at', 'verified_by', 'verified_at'
    ];

    public $timestamps = false;

    public function organization() {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
