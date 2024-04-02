<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\Patient;
use App\Models\TestResult;
use App\Models\User;
use App\Models\Role;

use App\Models\MedicalRecord;
use App\Models\UserRole;
use Exception;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpParser\Node\Expr\Cast\String_;

class DatabaseSeeder extends Seeder
{
    public function assignRole(String $roleName, User $user): void {
        $role = Role::where('name', $roleName)->first();
        UserRole::factory()->create([
            'role_id' => Role::where('name', $roleName)->first(),
            'user_id' => $user->id,
        ]);
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // try {
        //     //code...
        //     User::factory(1)->create();
        // } catch (Exception $ex) {
        //     //throw $th;
        //     echo $ex;
        // }

        // Doctor::factory(20)->create();

        // Patient::factory(12)->create();

        $user1 = User::factory()->create([
            'name' => 'Erick Ochieng',
            'email' => 'erickochieng766@gmail.com',
            'phone_number' => '254796584498',
            'password' => 'password',
        ]);

        // $firstDoctor = Doctor::first();

        // $user2 = User::factory()->create([
        //     'name' => 'Travis Cure',
        //     'email' => 'travis@phr.com',
        //     'role' => 'Doctor',
        //     'doctor_id' => $firstDoctor->id
        // ]);

        // $firstPatient = Patient::first();

        // $user3 = User::factory()->create([
        //     'name' => 'Steve Jobs',
        //     'email' => 'steve@phr.com',
        //     'role' => 'Patient',
        //     'patient_id' => $firstPatient->id
        // ]);

        // $roles = ["Administrator", "Doctor", "Patient", "ManageAppointments"];

        // foreach ($roles as $roleName) {
        //     Role::factory()->create([
        //         'name' => $roleName
        //     ]);
        // }

        // $this->assignRole('Administrator', $user1);
        // $this->assignRole('Doctor', $user2);
        // $this->assignRole('Patient', $user3);

        // $role = Role::where('name', $roleName)->first();

        // Appointment::factory(12)->create();

        // MedicalRecord::factory(207)->create();

        // LabTest::factory(307)->create();

        // TestResult::factory(407)->create();
    }
}
