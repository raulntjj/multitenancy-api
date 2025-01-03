<?php

namespace Database\Factories\Core;

use App\Core\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory {
    protected $model = Tenant::class;

    public function definition() {
        return [
            'name' => $this->faker->company,
            'slug' => $this->faker->slug,
            'db_host' => $this->faker->ipv4,
            'db_name' => $this->faker->word,
            'db_user' => 'root',
            'db_password' => 'root',
        ];
    }
}
