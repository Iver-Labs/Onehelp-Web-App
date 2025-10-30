<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $primaryKey = 'skill_id';
    protected $fillable = ['skill_name', 'description', 'category'];

    public function volunteers() {
        return $this->belongsToMany(Volunteer::class, 'volunteer_skills', 'skill_id', 'volunteer_id');
    }

    public function events() {
        return $this->belongsToMany(Event::class, 'event_skills', 'skill_id', 'event_id');
    }
}
