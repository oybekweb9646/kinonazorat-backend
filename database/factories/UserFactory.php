<?php

namespace Database\Factories;

use App\Core\Enums\Role\RoleEnum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => 'admin',
            'pin_fl' => 50109015360031,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'status' => 1,
            'role' => RoleEnum::_SUPER_ADMIN->value,
            'auth_type' => 'password',
        ];
    }
}
