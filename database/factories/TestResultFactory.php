<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\LabTest;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestResult>
 */
class TestResultFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $labTestId = LabTest::inRandomOrder()->first()->id;

        return [
            'lab_test_id' => $labTestId,
            'result' => fake()->paragraph,
        ];
    }
}
