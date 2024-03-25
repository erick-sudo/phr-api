<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AppointmentsController extends Controller
{

    public function transformPaginations(LengthAwarePaginator $paginator) {
        $transformedAppointments = [];
        foreach ($paginator->items() as $appointment) {
            // Retrieve additional information about the patient
            $patient = Patient::select('id', 'name')->find($appointment->patient_id);
    
            // Retrieve additional information about the doctor
            $doctor = Doctor::select('id', 'name')->find($appointment->doctor_id);
    
            // Create the transformed appointment with additional patient and doctor information
            $transformedAppointment = [
                'id' => $appointment->id,
                'patient' => $patient,
                'doctor' => $doctor,
                'status' => $appointment->status,
                'appointment_date' => $appointment->appointment_date,
                'reason' => $appointment->reason,
                'created_at' => $appointment->created_at,
                'updated_at' => $appointment->updated_at,
            ];
    
            // Add the transformed appointment to the list
            $transformedAppointments[] = $transformedAppointment;
        }
    
        return [
            'total' => $paginator->total(),
            'per_page' => $paginator->perPage(),
            'items' => $transformedAppointments,
            'current_page' => $paginator->currentPage()
        ];
    }

    
    /**
     * Show all appointments
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $size = $request->query('size', 10);

        $appointments = Appointment::orderBy('created_at', 'desc')->paginate($size, ['*'], 'page', $page);
        return response()->json($this->transformPaginations($appointments));
    }

    /** 
     * Show appointment details
     */
    public function show($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        return response()->json($appointment);
    }

    /**
     * Create a new appointment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'patient_id' => 'required|int',
            'doctor_id' => 'required|int',
            'appointment_date' => 'required|date',
            'reason' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $appointment = Appointment::create($request->all());

        return response()->json($appointment, 201);
    }

    /**
     * Update a appointment
     */
    public function update(Request $request, int $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'patient_id' => 'nullable|int',
            'appointment_id' => 'nullable|int',
            'status' => 'nullable|string|in:Pending,Approved,Closed',
            'appointment_date' => 'nullable|date',
            'reason' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $appointment->update($request->all());

        return response()->json($appointment, 202);
    }

    /**
     * Delete a appointment
     */
    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $appointment->delete();

        return response()->json(['message' => 'Appointment deleted']);
    }
}
