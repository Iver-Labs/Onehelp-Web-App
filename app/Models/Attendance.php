<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'attendance_id';
    protected $fillable = ['registration_id', 'check_in_time', 'check_out_time', 'status', 'notes'];

    public function registration() {
        return $this->belongsTo(EventRegistration::class, 'registration_id');
    }
}
