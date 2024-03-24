<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use Illuminate\Support\Facades\Gate;

class PatientsController extends Controller
{

    /**
     * Search patients
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'field_names' => 'required|array|in:name,email,phone',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = $request->input('query');
        $fieldNames = $request->input('field_names');

        $results = Patient::where(function ($queryBuilder) use ($query, $fieldNames) {
            foreach ($fieldNames as $fieldName) {
                $queryBuilder->orWhere($fieldName, 'like', '%' . $query . '%');
            }
        })->take(10)->get();

        return response()->json($results);
    }

    /**
     * Show all patients
     */
    public function index(Request $request) {
        $page = $request->query('page', 1);
        $size = $request->query('size', 10);
        
        $patients = Patient::orderBy('created_at', 'desc')->paginate($size, ['*'], 'page', $page);
        return response()->json($this->transformPagination($patients));
    }

    /**
     * Show patients details
     */
    public function show($id) {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        Gate::authorize('view', $patient);

        return response()->json($patient);
    }

    /**
     * Create a new patient
     */
    public function store(Request $request) {

        Gate::authorize('create', Patient::class);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'dob' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient = Patient::create($request->all());

        return response()->json($patient, 201);
    }

    /**
     * Update a patient
     */
    public function update(Request $request, int $id) {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $patient->update($request->all());

        return response()->json($patient, 202);
    }

    /**
     * Delete a patient
     */
    public function destroy($id) {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        $patient->delete();

        return response()->json(['message' => 'Patient deleted']);
    }

    /**
     * A patient's appointments
     */
    public function patientAppointments($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json($patient->appointments);
    }

    public function transformMedicalRecords($medicalRecords)
    {
        $transformedMedicalRecords = [];
        
        foreach ($medicalRecords as $record) {
            $doctor = Doctor::find($record->doctor_id);

            $transformedRecord = [
                'id' => $record->id,
                'patient_id' => $record->patient_id,
                'doctor' => [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                ],
                'symptoms' => $record->symptoms,
                'diagnosis' => $record->diagnosis,
                'treatment' => $record->treatment,
                'created_at' => $record->created_at,
                'updated_at' => $record->updated_at,
            ];

            $transformedMedicalRecords[] = $transformedRecord;
        }

        return $transformedMedicalRecords;
    }

    public function patientMedicalRecords($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        return response()->json($this->transformMedicalRecords($patient->medicalRecords));
    }
}