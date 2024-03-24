<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\MedicalRecordsController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\PatientsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ['Laravel' => app()->version()];
});

Route::prefix('patients')->group(function () {
    Route::get('/', [PatientsController::class, 'index']);
    Route::get('/{id}', [PatientsController::class, 'show']);
    Route::post('/', [PatientsController::class, 'store']);
    Route::patch('/{id}', [PatientsController::class, 'update']);
    Route::delete('/{id}', [PatientsController::class, 'destroy']);
    Route::get('/{id}/appointments', [PatientsController::class, 'patientAppointments']);
    Route::get('/{id}/medical_records', [PatientsController::class, 'patientMedicalRecords']);
    Route::post('/search', [PatientsController::class, 'search']);
});

Route::prefix('doctors')->group(function () {
    Route::get('/', [DoctorsController::class, 'index']);
    Route::get('/{id}', [DoctorsController::class, 'show']);
    Route::post('/', [DoctorsController::class, 'store']);
    Route::patch('/{id}', [DoctorsController::class, 'update']);
    Route::delete('/{id}', [DoctorsController::class, 'destroy']);
    Route::get('/{id}/appointments', [DoctorsController::class, 'doctorAppointments']);
    Route::get('/{id}/departments', [DoctorsController::class, 'doctorDepartments']);
    Route::post('/search', [DoctorsController::class, 'search']);
    Route::get('/specializations/tally', [DoctorsController::class, 'tallyDoctorsBySpecialization']);
});

Route::prefix('appointments')->group(function () {
    Route::get('/', [AppointmentsController::class, 'index']);
    Route::get('/{id}', [AppointmentsController::class, 'show']);
    Route::post('/', [AppointmentsController::class, 'store']);
    Route::patch('/{id}', [AppointmentsController::class, 'update']);
    Route::delete('/{id}', [AppointmentsController::class, 'destroy']);
});

Route::prefix('medicalrecords')->group(function () {
    Route::get('/{id}', [MedicalRecordsController::class, 'show']);
    Route::post('/', [MedicalRecordsController::class, 'store']);
    Route::patch('/{id}', [MedicalRecordsController::class, 'update']);
    Route::delete('/{id}', [MedicalRecordsController::class, 'destroy']);
});

require __DIR__.'/auth.php';
