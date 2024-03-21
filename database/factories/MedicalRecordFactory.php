<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Doctor;
use App\Models\Patient;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $patientId = Patient::inRandomOrder()->first()->id;
        $doctorId = Doctor::inRandomOrder()->first()->id;

        return [
            'patient_id' => $patientId,
            'doctor_id' => $doctorId,
            'symptoms' => fake()->paragraph,
            'diagnosis' => fake()->sentence,
            'treatment' => fake()->sentence,
        ];
    }
}
