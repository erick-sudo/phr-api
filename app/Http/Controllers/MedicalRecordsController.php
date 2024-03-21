<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Validator;

class MedicalRecordsController extends Controller
{
    public function show($id) {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['message' => 'MedicalRecord not found'], 404);
        }
        return response()->json($medicalRecord);
    }

    /**
     * Create a new patient
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|int',
            'doctor_id' => 'required|int',
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medicalRecord = MedicalRecord::create($request->all());

        return response()->json($medicalRecord, 201);
    }

    /**
     * Update a patient
     */
    public function update(Request $request, int $id) {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['message' => 'MedicalRecord not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'symptoms' => 'nullable|string',
            'diagnosis' => 'nullable|string',
            'treatment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $medicalRecord->update($request->all());

        return response()->json($medicalRecord, 202);
    }

    /**
     * Delete a medicalRecord
     */
    public function destroy($id) {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['message' => 'MedicalRecord not found'], 404);
        }

        $medicalRecord->delete();

        return response()->json(['message' => 'MedicalRecord deleted']);
    }
}
