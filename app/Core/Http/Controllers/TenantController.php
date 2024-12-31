<?php

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
use App\Core\Models\User;

trait AuthenticatedUser {
    use AuthenticatedUser;

    public function index(Request $request) {
        try {
            $user = $this->getAuthenticatedUser($request->bearerToken());


            return response()->json([
                'status_code' => 200,
                'status' => 'success',
                'payload' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => $e->getCode() ?: 500,
                'status' => 'failed',
                'details' => $e->getMessage(),
            ], $e->getCode() ?: 500);
        }
    }
}