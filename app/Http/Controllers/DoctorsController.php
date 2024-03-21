<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DoctorsController extends Controller
{
    /**
     * Search doctors
     */

    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string',
            'field_names' => 'required|array|in:name,email,phone,specialization',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $query = $request->input('query');
        $fieldNames = $request->input('field_names');

        $results = Doctor::where(function ($queryBuilder) use ($query, $fieldNames) {
            foreach ($fieldNames as $fieldName) {
                $queryBuilder->orWhere($fieldName, 'like', '%' . $query . '%');
            }
        })->take(10)->get();

        return response()->json($results);
    }

    public function tallyDoctorsBySpecialization()
    {
        // Retrieve the count of doctors grouped by their specialization
        $doctorCounts = Doctor::select('specialization', DB::raw('COUNT(*) as count'))
                             ->groupBy('specialization')
                             ->get();
    
        // Format the result
        $formattedResults = [];
        foreach ($doctorCounts as $doctorCount) {
            $formattedResults[$doctorCount->specialization] = $doctorCount->count;
        }
    
        return $formattedResults;
    }

    /**
     * Show all doctors
     */
    public function index(Request $request) {
        $page = $request->query('page', 1);
        $size = $request->query('size', 10);
        
        $doctors = Doctor::orderBy('created_at', 'desc')->paginate($size, ['*'], 'page', $page);
        return response()->json($this->transformPagination($doctors));
    }

    /**
     * Show doctor details
     */
    public function show($id) {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        return response()->json($doctor);
    }

    /**
     * Create a new doctor
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor = Doctor::create($request->all());

        return response()->json($doctor, 201);
    }

    /**
     * Update a doctor
     */
    public function update(Request $request, int $id) {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'specialization' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $doctor->update($request->all());

        return response()->json($doctor, 202);
    }

    /**
     * Delete a doctor
     */
    public function destroy($id) {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $doctor->delete();

        return response()->json(['message' => 'Doctor deleted']);
    }

    /**
     * A doctor's appointments
     */
    public function doctorAppointments($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor->appointments);
    }

    /**
     * A doctor's departments
     */
    public function doctorDepartments($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json($doctor->departments);
    }
}