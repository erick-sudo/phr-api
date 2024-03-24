<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\LabTest;
use App\Models\MedicalRecord;
use App\Models\Patient;

class DashController extends Controller
{
    public function count()
    {
        $doctors = Doctor::all()->count();
        $patients = Patient::all()->count();
        $appointments = Appointment::all()->count();
        $lab_tests = LabTest::all()->count();

        return response()->json([
            'doctors' => $doctors,
            'patients' => $patients,
            'appointments' => $appointments,
            'lab_tests' => $lab_tests
        ]);
    }

    public function pastSevenDays()
    {
        $pastSevenDays = Appointment::whereDate('date', '<=', date('Y-m-d'))->get();

        return response()->json($pastSevenDays);
    }
}
