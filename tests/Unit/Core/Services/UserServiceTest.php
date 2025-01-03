<?php

namespace Tests\Unit\Core\Services;

use App\Core\Models\User;
use App\Core\Services\UserService;
use Database\Factories\Core\UserFactory;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\CoreCase;

class UserServiceTest extends CoreCase {
    use DatabaseMigrations;

    public function testFindUser() {
        $user = UserFactory::new()->create(['email' => 'test@example.com']);
        $service = new UserService();
        $foundUser = $service->findUser(['email' => 'test@example.com']);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindById() {
        $user = UserFactory::new()->create();
        $service = new UserService();
        $foundUser = $service->findById($user->id);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testCreateUser() {
        $service = new UserService();
        $user = $service->createUser([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function testUpdateUser() {
        $user = UserFactory::new()->create(['name' => 'Old Name']);
        $service = new UserService();
        $updatedUser = $service->updateUser(['name' => 'New Name'], $user->id);

        $this->assertEquals('New Name', $updatedUser->name);
    }

    public function testDeleteUser() {
        $user = UserFactory::new()->create();
        $service = new UserService();
        $service->deleteUser($user->id);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
