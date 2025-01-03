<?php

namespace Tests\Unit\Tenant\Services;

use App\Tenant\Models\User;
use App\Tenant\Services\UserService;
use Database\Factories\Tenant\UserFactory;

use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Tests\TenantCase;

class UserServiceTest extends TenantCase {
    use DatabaseMigrations;

    public function testFindManyUsers() {
        $users = UserFactory::new()->count(3)->create();
        $service = new UserService();
        $users = $service->findManyUsers();

        $this->assertCount(3, $users);
    }

    public function testFindUserByEmail() {
        $user = UserFactory::new()->create(['email' => 'test@example.com']);
        $service = new UserService();
        $foundUser = $service->findUser(['email' => 'test@example.com']);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindUserByUser() {
        $user = UserFactory::new()->create(['user' => 'testuser']);
        $service = new UserService();
        $foundUser = $service->findUser(['user' => 'testuser']);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindById() {
        $user = UserFactory::new()->create();
        $service = new UserService();
        $foundUser = $service->findById($user->id);

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testFindByUser() {
        $user = UserFactory::new()->create(['user' => 'uniqueuser']);
        $service = new UserService();
        $foundUser = $service->findByUser('uniqueuser');

        $this->assertEquals($user->id, $foundUser->id);
    }

    public function testCreateUser() {
        $service = new UserService();
        $user = $service->createUser([
            'name' => 'Jane Doe',
            'user' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => 'securepassword',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertTrue(Hash::check('securepassword', $user->password));
    }

    public function testUpdateUser() {
        $user = UserFactory::new()->create(['user' => 'teste12', 'name' => 'Old Name']);
        $service = new UserService();
        $updatedUser = $service->updateUser(['name' => 'New Name'], 'teste12');

        $this->assertEquals('New Name', $updatedUser->name);
    }

    public function testDeleteUser() {
        $user = UserFactory::new()->create(['user' => 'deletableuser']);
        $service = new UserService();
        $service->deleteUser('deletableuser');

        $this->assertDatabaseMissing('users', ['user' => 'deletableuser']);
    }
}
