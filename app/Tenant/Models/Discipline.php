<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class Discipline extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'disciplines';

    protected $fillable = [
        'name',
        'concentration_area',
    ];

    public function timetables() {
        return $this->hasMany(Timetable::class, 'discipline_id');
    }

    public function classroom() {
        return $this->belongsToMany(Classroom::class, 'classroom_disciplines', 'discipline_id', 'classroom_id');
    }

    public function teachers() {
        return $this->belongsToMany(Teacher::class, 'teacher_classrooms', 'discipline_id', 'user_id');
    }
}