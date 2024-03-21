<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\TestResult;
use App\Models\User;

use App\Models\MedicalRecord;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Doctor::factory(10)->create();

        Patient::factory(12)->create();

        Appointment::factory()->create();

        MedicalRecord::factory(207)->create();

        LabTest::factory(307)->create();

        TestResult::factory(407)->create();
    }
}
