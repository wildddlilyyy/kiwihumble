<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'birthday' => fake()->date('Y-m-d', '2020-12-31'),
            'mom_name' => fake()->firstNameFemale(),
            'mom_phone' => fake()->phoneNumber(),
            'dad_name' => fake()->firstNameMale(),
            'dad_phone' => fake()->phoneNumber(),
            'login_password' => null,
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'is_admin' => false,
        ];
    }
}
