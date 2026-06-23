<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Stałe konta testowe
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@medbook.pl',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Specjalizacje — stały zestaw + dodatkowe z factory
        $baseSpecs = ['Kardiolog', 'Dermatolog', 'Pediatra', 'Neurolog', 'Ortopeda', 'Internista', 'Okulista'];
        foreach ($baseSpecs as $name) {
            Specialization::create(['name' => $name, 'description' => "Specjalista: {$name}"]);
        }
        Specialization::factory(8)->create();

        $specIds = Specialization::pluck('id')->toArray();

        // Testowy lekarz z fixed danymi
        $docUser = User::create([
            'name' => 'Dr Jan Kowalski',
            'email' => 'doctor@medbook.pl',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);
        $doctor = Doctor::create([
            'user_id' => $docUser->id,
            'license_number' => 'LEK-1234567',
            'bio' => 'Doświadczony lekarz z 15-letnim stażem.',
            'consultation_fee' => 150.00,
            'room' => '101',
        ]);
        $doctor->specializations()->attach([$specIds[0], $specIds[5]]);

        // 30 losowych lekarzy
        Doctor::factory(30)->create()->each(function (Doctor $d) use ($specIds) {
            $d->specializations()->attach(
                collect($specIds)->random(rand(1, 3))->toArray()
            );
        });

        // Testowy pacjent z fixed danymi
        $patUser = User::create([
            'name' => 'Anna Nowak',
            'email' => 'patient@medbook.pl',
            'password' => Hash::make('password'),
            'role' => 'patient',
        ]);
        Patient::create([
            'user_id' => $patUser->id,
            'pesel' => '44051401359', // valid checksum
            'phone' => '500600700',
            'birth_date' => '1990-01-01',
            'address' => 'ul. Testowa 1, Warszawa',
        ]);

        // 80 losowych pacjentów
        Patient::factory(80)->create();

        // 200 wizyt — unikamy kolizji terminów
        $doctors = Doctor::all();
        $patients = Patient::all();
        $created = 0;
        $attempts = 0;
        while ($created < 200 && $attempts < 1000) {
            $attempts++;
            $doc = $doctors->random();
            $pat = $patients->random();
            $date = now()->addDays(rand(-30, 60))->setTime(rand(8, 17), [0, 15, 30, 45][rand(0, 3)], 0);

            $exists = Appointment::where('doctor_id', $doc->id)
                ->where('appointment_date', $date)
                ->exists();
            if ($exists) continue;

            Appointment::create([
                'doctor_id' => $doc->id,
                'patient_id' => $pat->id,
                'specialization_id' => $doc->specializations->random()->id ?? null,
                'appointment_date' => $date,
                'status' => collect(['pending', 'confirmed', 'completed', 'cancelled'])->random(),
                'reason' => fake()->sentence(8),
                'notes' => rand(0, 1) ? fake()->paragraph() : null,
            ]);
            $created++;
        }
    }
}
