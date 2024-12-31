<?php

namespace App\Tenant\Http\Controllers;

use App\Tenant\Models\User;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Laravel\Lumen\Routing\Controller;

class AuthController extends Controller {
    public function login(Request $request) {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if (!password_verify($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Invalid password'], 401);
        }

        $payload = [
            'iss'  => 'tenant-api',
            'sub'  => $user->id,
            'role' => 'tenant',
            'iat'  => time(),
            'exp'  => time() + 3600,
        ];
        $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }
}
