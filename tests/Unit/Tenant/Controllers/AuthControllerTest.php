<?php

namespace Tests\Unit\Tenant\Controllers;

use App\Tenant\Models\User;
use Database\Factories\Tenant\UserFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Tests\TenantCase;

class AuthControllerTest extends TenantCase {
    use DatabaseMigrations;

    public function testLoginSuccess() {
        $user = UserFactory::new()->create([
            'email' => 'tenantuser@example.com',
            // 'user' => 'tenantuser',
            'password' => password_hash('password', PASSWORD_BCRYPT),
        ]);

        $response = $this->post('api/v1/tenant_teste/login', [
            'email' => 'tenantuser@example.com',
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
        $response = $this->post('api/v1/tenant_teste/login', [
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
        $response = $this->post('api/v1/tenant_teste/register', [
            'name' => 'Tenant User',
            'user' => 'tenantuser',
            'email' => 'tenantuser@example.com',
            'password' => 'password',
        ]);

        $response->seeJsonStructure([
            'status_code',
            'status',
            'payload' => [
                'user' => [
                    'name',
                    'user',
                    'email',
                ],
            ],
        ]);
        $response->assertResponseStatus(200);
        $this->seeInDatabase('users', ['email' => 'tenantuser@example.com']);
    }
}
