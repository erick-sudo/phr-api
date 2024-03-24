<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Doctor>
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

        $specializations = [
            "Internal Medicine",
            "Obstetrics and Gynecology",
            "Anesthesiology",
            "Psychiatry",
            "Radiology",
            "Orthopedics",
        ];

        return [
            'name' => fake()->name(),
            'specialization' => $specializations[random_int(0, count($specializations) - 1)],
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail()
        ];
    }
}
