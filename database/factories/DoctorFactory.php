<?php

namespace Database\Factories;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'doctor', 'password' => Hash::make('password')]),
            'license_number' => fake()->unique()->regexify('[A-Z]{3}-[0-9]{7}'),
            'bio' => fake()->paragraph(),
            'consultation_fee' => fake()->randomFloat(2, 80, 500),
            'room' => fake()->numberBetween(100, 405),
            'is_active' => true,
        ];
    }
}
