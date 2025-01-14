<?php

namespace App\Tenant\Services;

use App\Tenant\Models\Discipline;
use Illuminate\Support\Facades\DB;

class DisciplineService {

    public function findManyDisciplines() {
        return Discipline::all();
    }
    
    public function findById(Int $disciplineId) {
        return Discipline::with([
            'timetables',
            'disciplines',
            'teachers',
        ])->where('id', $disciplineId)->first();
    }

    public function createDiscipline(Array $data) {
        return Discipline::create([
            'name' => $data['name'],
            'concentration_area' => $data['concentration_area'],
        ]);
    }

    public function updateDiscipline(array $data, int $disciplineId) {
        $discipline = Discipline::where('id', $disciplineId)->firstOrFail();
        $discipline->fill([
            'name' => $data['name'] ?? $discipline->name,
            'concentration_area' => $data['concentration_area'] ?? $discipline->concentration_area,
        ])->save();

        return $discipline;
    }
    
    public function deleteDiscipline(Int $disciplineId) {
        return Discipline::where('id', $disciplineId)->delete();
    }
}