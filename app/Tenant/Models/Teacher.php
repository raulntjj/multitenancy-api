<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model {
    protected $connection = 'tenant';
    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'registration',
    ];

    public function rejections() {
        return $this->belongsToMany(Schedule::class, 'teacher_schedule_rejections', 'user_id', 'schedule_id');
    }

    public function timetables() {
        return $this->hasMany(Timetable::class, 'teacher_id');
    }

    public function qualifications() {
        return $this->hasMany(AcademicQualification::class, 'user_id');
    }

    public function classrooms() {
        return $this->belongsToMany(Classroom::class, 'teacher_classrooms', 'user_id', 'classroom_id');
    }

    public function disciplines() {
        return $this->belongsToMany(Discipline::class, 'teacher_discipines', 'user_id', 'discipline_id');
    }
}