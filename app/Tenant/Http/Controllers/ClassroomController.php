<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\ClassroomService;
use App\Exceptions\HandleException;

class ClassroomController extends Controller {

    protected $classroomService;
    public function __construct(ClassroomService $classroomService) {
        $this->classroomService = $classroomService;
    }

    public function index(Request $request) {
        try {
            $classrooms = $this->classroomService->findManyClassrooms();
            if (!$classrooms) {
                throw new HandleException('Classroom not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $classrooms,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function show(Request $request, Int $name) {
        try {
            $classroom = $this->classroomService->findByName($name);
            if (!$classroom) {
                throw new HandleException('Classroom not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $classroom,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function showByRegistration(Request $request, String $registration) {
        try {
            $classroom = $this->classroomService->findByRegistration($registration);
            if (!$classroom) {
                throw new HandleException('Classroom not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $classroom,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function create(Request $request) {
        try {
            $classroom = $this->classroomService->createClassroom($request->all());
            if (!$classroom) {
                throw new HandleException('Something went wrong', 500);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $classroom,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, Int $userId) {
        try {
            $classroom = $this->classroomService->updateClassroom($request->all(), $userId);
            if (!$classroom) {
                throw new HandleException('Classroom not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $classroom,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request, Int $userId) {
        try {
            $classroom = $this->classroomService->deleteClassroom($userId);
            if (!$classroom) {
                throw new HandleException('Classroom not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $classroom,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}