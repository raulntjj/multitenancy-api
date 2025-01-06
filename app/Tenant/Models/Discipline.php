<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model {
    protected $connection = 'tenant';
    protected $table = 'disciplines';

    protected $fillable = [
        'name',
        'shift',
        'timetable_id',
    ];

    public function timetables() {
        return $this->hasMany(Timetable::class, 'classroom_id');
    }

    public function classroom() {
        return $this->belongsToMany(Discipline::class, 'classroom_discipines', 'discipline_id', 'classroom_id');
    }

    public function teachers() {
        return $this->belongsToMany(Classroom::class, 'teacher_classrooms', 'discipline_id', 'user_id');
    }
}