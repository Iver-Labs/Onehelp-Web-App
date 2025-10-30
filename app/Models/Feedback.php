<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;
    protected $table = 'feedbacks';
    
    protected $primaryKey = 'feedback_id';
    protected $fillable = ['registration_id', 'rating', 'comment', 'created_at'];
    public $timestamps = false;

    public function registration() {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }
}
