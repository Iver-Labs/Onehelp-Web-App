<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventSkill extends Model
{
    use HasFactory;

    protected $primaryKey = 'event_skill_id';
    protected $fillable = ['event_id', 'skill_id', 'is_required'];
}
