<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\DisciplineService;
use App\Exceptions\HandleException;

class DisciplineController extends Controller {

    protected $disciplineService;
    public function __construct(DisciplineService $disciplineService) {
        $this->disciplineService = $disciplineService;
    }

    public function index(Request $request) {
        try {
            $disciplines = $this->disciplineService->findManyDisciplines();
            if (!$disciplines) {
                throw new HandleException('Discipline not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $disciplines,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function show(Request $request, Int $disciplineId) {
        try {
            $discipline = $this->disciplineService->findById($disciplineId);
            if (!$discipline) {
                throw new HandleException('Discipline not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $discipline,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function create(Request $request) {
        try {
            $discipline = $this->disciplineService->createDiscipline($request->all());
            if (!$discipline) {
                throw new HandleException('Something went wrong', 500);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $discipline,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function update(Request $request, Int $disciplineId) {
        try {
            $discipline = $this->disciplineService->updateDiscipline($request->all(), $disciplineId);
            if (!$discipline) {
                throw new HandleException('Discipline not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $discipline,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }

    public function destroy(Request $request, Int $disciplineId) {
        try {
            $discipline = $this->disciplineService->deleteDiscipline($disciplineId);
            if (!$discipline) {
                throw new HandleException('Discipline not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $discipline,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e);
        }
    }
}