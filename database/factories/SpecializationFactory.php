<?php

namespace Database\Factories;

use App\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Specialization>
 */
class SpecializationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Alergolog', 'Anestezjolog', 'Chirurg', 'Diabetolog', 'Endokrynolog',
                'Gastrolog', 'Ginekolog', 'Hematolog', 'Laryngolog', 'Onkolog',
                'Psychiatra', 'Pulmonolog', 'Radiolog', 'Reumatolog', 'Urolog',
            ]),
            'description' => fake()->sentence(10),
            'is_active' => true,
        ];
    }
}
