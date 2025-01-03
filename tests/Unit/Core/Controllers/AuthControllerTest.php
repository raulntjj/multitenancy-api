<?php

namespace Tests\Unit\Core\Controllers;

use App\Core\Models\User;
use Database\Factories\Core\UserFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\CoreCase;

class AuthControllerTest extends CoreCase {
    use DatabaseMigrations;

    public function testLoginSuccess() {
        $user = UserFactory::new()->create([
            'email' => 'test@example.com',
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);

        $response = $this->post('api/v1/core/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->seeJsonStructure([
            'status_code',
            'status',
            'payload' => [
                'user',
                'token',
                'type',
                'expires_in',
                'issuing_at',
            ],
        ]);
        $response->assertResponseStatus(200);
    }

    public function testLoginFailure() {
        $response = $this->post('api/v1/core/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        $response->seeJson([
            "status_code" => 404,
            "status" => "failed",
            "details" => "Credentials does not match"
        ]);
        $response->assertResponseStatus(404);
    }

    public function testRegisterSuccess() {
        $response = $this->post('api/v1/core/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->seeJsonStructure([
            'status_code',
            'status',
            'payload' => [
                'user' => [
                    'name',
                    'email',
                ],
            ],
        ]);
        $response->assertResponseStatus(200);
        $this->seeInDatabase('users', ['email' => 'john@example.com']);
    }
}
