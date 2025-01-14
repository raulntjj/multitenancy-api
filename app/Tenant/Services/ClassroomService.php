<?php

namespace App\Tenant\Services;

use App\Tenant\Models\Classroom;
use Illuminate\Support\Facades\DB;

class ClassroomService {

    public function findManyClassrooms() {
        return Classroom::all();
    }
    
    public function findById(Int $classroomId) {
        return Classroom::with([
            'timetables',
            'disciplines',
            'teachers',
        ])->where('id', $classroomId)->first();
    }

    public function createClassroom(Array $data) {
        return Classroom::create([
            'name' => $data['name'],
            'shift' => $data['shift'],
        ]);
    }

    public function updateClassroom(array $data, int $classroomId) {
        $classroom = Classroom::where('id', $classroomId)->firstOrFail();
        $classroom->fill([
            'name' => $data['name'] ?? $classroom->name,
            'shift' => $data['shift'] ?? $classroom->shift,
        ])->save();

        return $classroom;
    }
    
    public function deleteClassroom(Int $classroomId) {
        return Classroom::where('id', $classroomId)->delete();
    }
}