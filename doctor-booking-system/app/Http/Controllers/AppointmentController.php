<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function addAppointment(Request $request)
    {
        try {

            $header = $request->header('Authorization');
            $user = User::where('device_token', $header)->first();
            if ($user == "") {

                return response()->json(['message' => 'User is Invalid !'], 401);
            }

            // Validate the input
            $validator = Validator::make($request->all(), [
                'appointment_date' => 'required|date',
                'appointment_start_time' => 'required|date_format:H:i',
                'doctor_id' => 'required|exists:doctors,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $appointment_date = date('Y-m-d', strtotime($request->input('appointment_date')));

            // Calculate the end_time as start_time + 30 minutes
            $appointment_start_time = $request->input('appointment_start_time');
            $appointment_end_time = date('H:i', strtotime($appointment_start_time . ' +30 minutes'));

            // Check if there's a conflicting appointment
            $conflictingAppointment = Appointment::where('appointment_date', $appointment_date)
                ->where(function ($query) use ($appointment_start_time, $appointment_end_time) {
                    $query->whereBetween('appointment_start_time', [$appointment_start_time, $appointment_end_time])
                        ->orWhereBetween('appointment_end_time', [$appointment_start_time, $appointment_end_time]);
                })
                ->first();

            if ($conflictingAppointment) {
                return response()->json(['message' => 'An appointment already exists at this date and time'], 409);
            }

            // Create the appointment
            $appointment = new Appointment([
                'appointment_date' => $appointment_date,
                'appointment_start_time' => $appointment_start_time,
                'appointment_end_time' => $appointment_end_time,
                'patient_id' => $user->id,
                'doctor_id' => $request->input('doctor_id'),
                'status' => 'RSVP', // Initial status is RSVP
            ]);

            $appointment->save();

            return response()->json(['message' => 'Appointment added successfully'], 201);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    // Update an appointment
    public function updateAppointment(Request $request)
    {
        try {

            $header = $request->header('Authorization');
            $user = User::where('device_token', $header)->first();
            if ($user == "") {

                return response()->json(['message' => 'User is Invalid !'], 401);
            }

            $request->validate([
                'appointment_date' => 'required|date',
                'appointment_start_time' => 'required|date_format:H:i',
            ]);

            $appointment_date = date('Y-m-d', strtotime($request->input('appointment_date')));

            // Calculate the end_time as start_time + 30 minutes
            $appointment_start_time = $request->input('appointment_start_time');
            $appointment_end_time = date('H:i', strtotime($appointment_start_time . ' +30 minutes'));

            $conflictingAppointment = Appointment::where('appointment_date', $appointment_date)
                ->where(function ($query) use ($appointment_start_time, $appointment_end_time) {
                    $query->whereBetween('appointment_start_time', [$appointment_start_time, $appointment_end_time])
                        ->orWhereBetween('appointment_end_time', [$appointment_start_time, $appointment_end_time]);
                })
                ->first();

            if ($conflictingAppointment) {
                return response()->json(['message' => 'An appointment already exists at this date and time'], 409);
            }

            // Update the appointment
            $appointment = Appointment::findOrFail($user->id);
            $appointment->update([
                'appointment_date' => $appointment_date,
                'appointment_start_time' => $appointment_start_time,
                'appointment_end_time' => $appointment_end_time,
            ]);

            return response()->json(['message' => 'Appointment updated successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    // Change appointment status (cancel/reject/postpone)
    public function changeStatus(Request $request, $id)
    {
        try {
            $header = $request->header('Authorization');
            $admin = Doctor::where('device_token', $header)->first();
            if ($admin == "") {
                return response()->json(['message' => 'Doctor is Invalid !'], 401);
            }

            $appointment = Appointment::findOrFail($id);

            if ($request->status === 'postponed') {

                $validator = Validator::make($request->all(), [
                    'appointment_date' => 'required|date_format:Y-m-d',
                    'appointment_start_time' => 'required|date_format:H:i',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => $validator->errors()], 400);
                }

                $appointment_date = $request->appointment_date;

                $newStartTime = $request->appointment_start_time;

                // Calculate new end_time based on new start_time + 30 minutes
                $newEndTime = date('H:i', strtotime($newStartTime . ' +30 minutes'));

                $conflictingAppointment = Appointment::where('appointment_date', $appointment_date)
                    ->where(function ($query) use ($newStartTime, $newEndTime) {
                        $query->whereBetween('appointment_start_time', [$newStartTime, $newEndTime])
                            ->orWhereBetween('appointment_end_time', [$newStartTime, $newEndTime]);
                    })
                    ->first();

                if ($conflictingAppointment) {
                    return response()->json(['message' => 'An appointment already exists at this date and time'], 409);
                }
                // Update appointment start_time and end_time
                $appointment->appointment_start_time = $newStartTime;
                $appointment->appointment_end_time = $newEndTime;
                $appointment->appointment_date = $appointment_date;
            }

            // Update the status
            $appointment->status = $request->status; //'approved', 'cancelled', 'postponed'

            $appointment->save();

            return response()->json(['message' => 'Appointment status updated successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function listAllAppointments(Request $request)
    {
        try {

            if ($request->has('date') != null || $request->has('date') != "") {
                $bookedAppointments = Appointment::where('appointment_date', $request->date)->get();

                if ($bookedAppointments == "") {

                    return response()->json(['message' => 'No Appointment Booked for that Day!'], 401);
                }
                return response()->json(['booked_appointments' => $bookedAppointments], 200);

            }
            $appointments = Appointment::all();

            return response()->json(['data' => $appointments], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function listAppointmentsByDoctor(Request $request)
    {
        try {

            $header = $request->header('Authorization');
            $admin = Doctor::where('device_token', $header)->first();
            if ($admin == "") {

                return response()->json(['message' => 'Doctor is Invalid !'], 401);
            }

            if ($request->has('date') != null || $request->has('date') != "") {
                $bookedAppointments = Appointment::where('appointment_date', $request->date)
                    ->where('doctor_id', $admin->id)
                    ->get();

                if ($bookedAppointments == "") {

                    return response()->json(['message' => 'No Appointment Booked for that Day!'], 401);
                }
                return response()->json(['booked_appointments' => $bookedAppointments], 200);
            }

            $appointments = Appointment::where('doctor_id', $admin->id)->get();

            return response()->json(['data' => $appointments], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function listAppointmentsByUser(Request $request)
    {
        try {

            $header = $request->header('Authorization');
            $user = User::where('device_token', $header)->first();
            if ($user == "") {

                return response()->json(['message' => 'User is Invalid !'], 401);
            }

            if ($request->has('date') != null || $request->has('date') != "") {
                $bookedAppointments = Appointment::where('appointment_date', $request->date)
                    ->where('patient_id', $user->id)
                    ->get();

                if ($bookedAppointments == "") {

                    return response()->json(['message' => 'No Appointment Booked for that Day!'], 401);
                }
                return response()->json(['booked_appointments' => $bookedAppointments], 200);
            }

            $appointments = Appointment::where('patient_id', $user->id)->get();

            return response()->json(['data' => $appointments], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function getAllBookedAppointmentsOnDate(Request $request)
    {
        try {
            $date = $request->input('date'); // Date in 'Y-m-d' format
            $bookedAppointments = Appointment::where('appointment_date', $date)->get();

            return response()->json(['booked_appointments' => $bookedAppointments], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

}
