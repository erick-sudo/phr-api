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
        // User::factory(10)->create();

        $user1 = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'admin@phr.com',
            'role' => 'Administrator',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Travis Cure',
            'email' => 'travis@phr.com',
            'role' => 'Doctor',
        ]);

        $user3 = User::factory()->create([
            'name' => 'Steve Jobs',
            'email' => 'steve@phr.com',
            'role' => 'Patient',
        ]);

        $roles = ["Administrator", "Doctor", "Patient", "ManageAppointments"];

        foreach ($roles as $roleName) {
            Role::factory()->create([
                'name' => $roleName
            ]);
        }

        $this->assignRole('Administrator', $user1);
        $this->assignRole('Doctor', $user2);
        $this->assignRole('Patient', $user3);

        $role = Role::where('name', $roleName)->first();

        Doctor::factory(20)->create();

        Patient::factory(12)->create();

        Appointment::factory(12)->create();

        MedicalRecord::factory(207)->create();

        LabTest::factory(307)->create();

        TestResult::factory(407)->create();
    }
}
