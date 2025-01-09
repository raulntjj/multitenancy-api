<?php

namespace App\Tenant\Services;

use App\Tenant\Models\Classroom;
use Illuminate\Support\Facades\DB;

class ClassroomService {

    public function findManyClassrooms() {
        return Classroom::all();
    }
    
    public function findByUser(Int $classroomId) {
        return Classroom::with([
            'qualifications',
            'classrooms',
            'disciplines',
            'timetables',
            'rejections',
        ])->where('user_id', $classroomId)->first();
    }

    public function findByName(Int $classroomId) {
        return Classroom::with([
            'qualifications',
            'classrooms',
            'disciplines',
            'timetables',
            'rejections',
        ])->where('user_id', $classroomId)->first();
    }

    public function createClassroom(Array $data) {
        return Classroom::create([
            'user_id' => $data['user_id'],
            'registration' => $data['registration'],
        ]);
    }

    public function updateClassroom(array $data, int $classroomId) {
        return Classroom::where('user_id', $classroomId)->first();

    }
    
    public function deleteClassroom(Int $classroomId) {
        return Classroom::where('user_id', $classroomId)->delete();
    }
}