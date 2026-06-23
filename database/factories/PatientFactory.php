<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Patient>
 */
class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'patient', 'password' => Hash::make('password')]),
            'pesel' => $this->generatePesel(),
            'phone' => fake()->numerify('5########'),
            'birth_date' => fake()->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d'),
            'address' => fake()->streetAddress() . ', ' . fake()->city(),
            'is_active' => true,
        ];
    }

    private function generatePesel(): string
    {
        $weights = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
        do {
            $digits = '';
            for ($i = 0; $i < 10; $i++) $digits .= random_int(0, 9);
            $sum = 0;
            for ($i = 0; $i < 10; $i++) $sum += ((int) $digits[$i]) * $weights[$i];
            $checksum = (10 - ($sum % 10)) % 10;
            $pesel = $digits . $checksum;
        } while (\App\Models\Patient::where('pesel', $pesel)->exists());
        return $pesel;
    }
}
