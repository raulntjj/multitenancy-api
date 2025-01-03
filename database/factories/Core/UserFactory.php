<?php

namespace Database\Factories\Core;

use App\Core\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory {
    protected $model = User::class;
    
    public function definition() {
        return [
            'name' => $this->faker->name, // Nome fictício
            'email' => $this->faker->unique()->safeEmail, // E-mail único
            'password' => bcrypt('password'), // Senha criptografada
        ];
    }
}
