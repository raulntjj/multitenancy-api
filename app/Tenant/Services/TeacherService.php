<?php

namespace App\Tenant\Services;

use App\Tenant\Models\Teacher;
use Illuminate\Support\Facades\DB;

class TeacherService {

    public function findManyTeachers() {
        return Teacher::all();
    }
    
    public function findByUser(Int $userId) {
        return Teacher::with([
            'qualifications',
            'classrooms',
            'disciplines',
            'timetables',
            'rejections',
        ])->where('user_id', $userId)->first();
    }

    public function findByRegistration(String $registration) {
        return Teacher::with([
            'qualifications',
            'classrooms',
            'disciplines',
            'timetables',
            'rejections',
        ])->where('registration', $registration)->first();
    }

    public function createTeacher(Array $data) {
        return Teacher::create([
            'user_id' => $data['user_id'],
            'registration' => $data['registration'],
        ]);
    }

    public function updateTeacher(array $data, int $userId) {
        $userIdToUpdate = $data['user_id'] ?? null;
        $registrationToUpdate = $data['registration'] ?? null;
        
        $affectedRows = DB::update(
            'UPDATE teachers SET user_id = ?, registration = ? WHERE user_id = ?',
            [
                $userIdToUpdate ?? $userId,  // Novo valor para `user_id`
                $registrationToUpdate,       // Novo valor para `registration`
                $userId                      // Condição para encontrar o registro
            ]
        );
            
        return Teacher::where('user_id', $userId)->first();

    }
    
    public function deleteTeacher(Int $userId) {
        return Teacher::where('user_id', $userId)->delete();
    }
}