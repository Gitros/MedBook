<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-30 days', '+60 days');
        $date->setTime(fake()->numberBetween(8, 17), fake()->randomElement([0, 15, 30, 45]));

        return [
            'doctor_id' => Doctor::factory(),
            'patient_id' => Patient::factory(),
            'specialization_id' => null,
            'appointment_date' => $date,
            'status' => fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'reason' => fake()->sentence(8),
            'notes' => fake()->optional()->paragraph(),
        ];
    }
}
