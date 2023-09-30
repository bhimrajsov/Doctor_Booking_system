<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DrAdminController extends Controller
{

    public function adminLogin(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|string',
                'password' => 'required|string',

            ]);

            $password = $request->input('password');
            $email = $request->input('email');
            //    $token = $request->input('token');
            $result = Doctor::where('email', '=', $email)->first();

            if ($result == "") {

                return response()->json(['message' => 'Doctor email and password not valid !'], 401);
            }

            if (!is_null($result) || $result != "") {
                if (Hash::check($password, $result->password)) {

                    // $tdata =  DB::table('admin')->where('email', $email)->update(['device_token' => $token]);
                    $data = Doctor::where('email', $email)->first();

                    return response()->json(['message' => 'Doctor login successfully', 'result' => $data], 200);
                } else {
                    return response()->json(['message' => 'Doctor email and password not valid !'], 401);
                }

            }
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    // public function adminLogin(Request $request)
    // {
    //     try {
    //         $this->validate($request, [
    //             'email' => 'required|string',
    //             'password' => 'required|string',
    //         ]);

    //         // Attempt to authenticate the user
    //         if (auth()->attempt($request->only('email', 'password'))) {
    //             // Authentication successful
    //             $token = auth()->user()->createToken('authToken')->accessToken;
    //             $doctor = Doctor::where('email', $request->email)->first();

    //             return response()->json(['data' => $doctor, 'access_token' => $token], 200);
    //         } else {
    //             // Authentication failed
    //             return response()->json(['message' => 'Unauthorized'], 401);
    //         }
    //     } catch (\Exception $exception) {
    //         return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
    //     }
    // }

    public function adminUpdate(Request $request)
    {
        try {
            $header = $request->header('Authorization');
            $admin = Doctor::where('device_token', $header)->first();
            if ($admin == "") {

                return response()->json(['message' => 'Doctor is Invalid !'], 401);
            }

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $dateOfBirthFormatted = date('Y-m-d', strtotime($request->DOB));

            if ($request->hasFile('image')) {
                $original_filename = $request->file('image')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './images/doctor/';
                $image = 'Doctor-' . time() . '.' . $file_ext;
                $request->file('image')->move($destination_path, $image);
                $result = $destination_path . $image;

                // Create a new doctor
                $doctor = Doctor::updateOrCreate(['id' => $admin->id], [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'image' => $result,
                    'speciality' => $request->speciality,
                    'languages' => $request->languages,
                    'education' => $request->education,
                    'DOB' => $dateOfBirthFormatted,
                    'gender' => $request->gender, //'Male', 'Female', 'Other'
                ]);

            } else {
                $doctor = Doctor::updateOrCreate(['id' => $admin->id], [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'speciality' => $request->speciality,
                    'languages' => $request->languages,
                    'education' => $request->education,
                    'DOB' => $dateOfBirthFormatted,
                    'gender' => $request->gender, //'Male', 'Female', 'Other'
                ]);
            }

            return response()->json(['message' => 'Doctor information updated successfully', 'data' => $doctor], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function adminSignup(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:doctors',
                'phone' => 'required|unique:doctors',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $dateOfBirthFormatted = date('Y-m-d', strtotime($request->DOB));

            if ($request->hasFile('image')) {
                $original_filename = $request->file('image')->getClientOriginalName();
                $original_filename_arr = explode('.', $original_filename);
                $file_ext = end($original_filename_arr);
                $destination_path = './images/doctor/';
                $image = 'Doctor-' . time() . '.' . $file_ext;
                $request->file('image')->move($destination_path, $image);
                $result = $destination_path . $image;

                // Create a new doctor
                $doctor = Doctor::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $result,
                    'password' => Hash::make($request->password),
                    'speciality' => $request->speciality,
                    'languages' => $request->languages,
                    'education' => $request->education,
                    'DOB' => $dateOfBirthFormatted,
                    'gender' => $request->gender, //'Male', 'Female', 'Other'
                    'device_token' => Crypt::encryptString(date('YMDHms')),
                ]);

            } else {
                $doctor = Doctor::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'speciality' => $request->speciality,
                    'languages' => $request->languages,
                    'education' => $request->education,
                    'DOB' => $dateOfBirthFormatted,
                    'gender' => $request->gender, //'Male', 'Female', 'Other'
                    'device_token' => Crypt::encryptString(date('YMDHms')),
                ]);
            }

            return response()->json(['message' => 'Doctor registered successfully', 'data' => $doctor], 201);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function getAdminDetails(Request $request)
    {
        try {

            // $user = auth()->user();
            $header = $request->header('Authorization');
            $admin = Doctor::where('device_token', $header)->first();
            if ($admin == "") {

                return response()->json(['message' => 'Doctor is Invalid !'], 401);
            }

            $doctors = Doctor::where('id', $admin->id)->get();

            return response()->json(['message' => 'Get Admin Details Successfully', 'doctor' => $doctors], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function getAllAdminDetails(Request $request)
    {
        try {
            $doctors = Doctor::all();
            return response()->json(['message' => 'Get All Admin Details Successfully', 'doctors' => $doctors], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

    public function adminDelete($id)
    {
        try {
            $doctor = Doctor::find($id);
            if (!$doctor) {
                return response()->json(['message' => 'Doctor not found'], 404);
            }
            $doctor->delete();
            return response()->json(['message' => 'Doctor deleted successfully'], 200);
        } catch (\Exception $exception) {
            return response()->json(['status' => $exception->getCode(), 'message' => $exception->getMessage()]);
        }
    }

}
