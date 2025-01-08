<?php

namespace App\Tenant\Http\Controllers;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;
use App\Tenant\Services\TeacherService;
use App\Exceptions\HandleException;

class TeacherController extends Controller {

    protected $teacherService;
    public function __construct(TeacherService $teacherService) {
        $this->teacherService = $teacherService;
    }

    public function index(Request $request) {
        try {
            $teachers = $this->teacherService->findManyTeachers();
            if (!$teachers) {
                throw new HandleException('Teacher not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $teachers,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function show(Request $request, Int $userId) {
        try {
            $teacher = $this->teacherService->findByUser($userId);
            if (!$teacher) {
                throw new HandleException('Teacher not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $teacher,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function showByRegistration(Request $request, String $registration) {
        try {
            $teacher = $this->teacherService->findByRegistration($registration);
            if (!$teacher) {
                throw new HandleException('Teacher not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $teacher,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function create(Request $request) {
        try {
            $teacher = $this->teacherService->createTeacher($request->all());
            if (!$teacher) {
                throw new HandleException('Something went wrong', 500);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $teacher,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function update(Request $request, Int $userId) {
        try {
            $teacher = $this->teacherService->updateTeacher($request->all(), $userId);
            if (!$teacher) {
                throw new HandleException('Teacher not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $teacher,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }

    public function destroy(Request $request, Int $userId) {
        try {
            $teacher = $this->teacherService->deleteTeacher($userId);
            if (!$teacher) {
                throw new HandleException('Teacher not found', 404);
            }

            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $teacher,
            ]);
        } catch (\Exception $e) {
            throw new HandleException($e->getMessage(), $e->getCode());
        }
    }
}