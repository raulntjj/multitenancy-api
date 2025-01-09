<?php

namespace App\Tenant\Models;

use Illuminate\Database\Eloquent\Model;
use App\Tenant\Traits\HasUserActions;

class Classroom extends Model {
    use HasUserActions;

    protected $connection = 'tenant';
    protected $table = 'classrooms';

    protected $fillable = [
        'name',
        'concentration_area',
    ];

    public function timetables() {
        return $this->hasMany(Timetable::class, 'classroom_id');
    }

    public function disciplines() {
        return $this->belongsToMany(Discipline::class, 'classroom_disciplines', 'classroom_id', 'discipline_id');
    }

    public function teachers() {
        return $this->belongsToMany(Classroom::class, 'teacher_classrooms', 'classroom_id', 'user_id');
    }
}