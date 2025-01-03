<?php

namespace Database\Factories\Tenant;

use App\Tenant\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {
    protected $model = User::class;
    
    public function definition() {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'user' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
        ];
    }
}
