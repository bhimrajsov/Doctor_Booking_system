<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function addUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'required',
                'DOB' => 'required',
                'gender' => 'required|string',
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
                $destination_path = './images/user/';
                $image = 'User-' . time() . '.' . $file_ext;
                $request->file('image')->move($destination_path, $image);
                $result = $destination_path . $image;

                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'image' => $result,
                    'password' => Hash::make($request->password),
                    'DOB' => $dateOfBirthFormatted,
                    'gender' => $request->gender, //'Male', 'Female', 'Other'
                    'address' => $request->address,
                    'weight' => $request->weight,
                    'height' => $request->height,
                    'device_token' => Crypt::encryptString(date('YMDHms')),
                ]);

            } else {
                $user = User::create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'DOB' => $dateOfBirthFormatted,
                    'gender' => $request->gender, //'Male', 'Female', 'Other'
                    'address' => $request->address,
                    'weight' => $request->weight,
                    'height' => $request->height,
                    'device_token' => Crypt::encryptString(date('YMDHms')),
                ]);
            }

            return response()->json(['message' => 'User added successfully'], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error adding user', 'error' => $exception->getMessage()], 500);
        }
    }

    public function userLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',

        ]);

        $password = $request->input('password');
        $email = $request->input('email');
        $result = User::where('email', '=', $email)->first();

        if ($result == "") {

            return response()->json(['message' => 'Doctor email and password not valid !'], 401);
        }

        if (!is_null($result) || $result != "") {
            if (Hash::check($password, $result->password)) {

                // $tdata =  DB::table('admin')->where('email', $email)->update(['device_token' => $token]);
                $data = User::where('email', $email)->first();

                return response()->json(['message' => 'Doctor login successfully', 'result' => $data], 200);
            } else {
                return response()->json(['message' => 'Doctor email and password not valid !'], 401);
            }

        }
    }

    // public function userLogin(Request $request)
    // {
    //     try {
    //         $this->validate($request, [
    //             'email' => 'required|email',
    //             'password' => 'required|string',
    //         ]);

    //         if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
    //             // Authentication successful
    //             $user = Auth::user();
    //             $token = $user->createToken('authToken')->accessToken;

    //             return response()->json(['user' => $user, 'access_token' => $token], 200);
    //         } else {
    //             return response()->json(['message' => 'Invalid credentials'], 401);
    //         }
    //     } catch (\Exception $exception) {
    //         return response()->json(['message' => 'Error during login', 'error' => $exception->getMessage()], 500);
    //     }
    // }

    public function getAllUser()
    {
        $users = User::all();
        return response()->json(['message' => 'Get All User Details Successfully', 'data' => $users], 200);
    }

    public function getUserid(Request $request)
    {

        $header = $request->header('Authorization');
        $user = User::where('device_token', $header)->first();
        if ($user == "") {

            return response()->json(['message' => 'User is Invalid !'], 401);
        }

        $user = User::find($user->id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json(['message' => 'Get User Details Successfully', 'data' => $user], 200);
    }

    public function updateUser(Request $request)
    {
        $header = $request->header('Authorization');
        $user = User::where('device_token', $header)->first();
        if ($user == "") {

            return response()->json(['message' => 'User is Invalid !'], 401);
        }

        try {
            // Validate user input
            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'DOB' => 'required|date',
                'gender' => 'required|string',
            ]);


            $dateOfBirthFormatted = date('Y-m-d', strtotime($request->DOB));

            // Update user data
            $user->update(['id' => $user->id],[
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'DOB' => $dateOfBirthFormatted,
                'gender' => $request->gender, //'Male', 'Female', 'Other'
                'address' => $request->address,
                'weight' => $request->weight,
                'height' => $request->height,
            ]);

            return response()->json(['message' => 'User updated successfully', 'data' => $user], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => 'Error updating user', 'error' => $exception->getMessage()], 500);
        }
    }

    public function userDelete(Request $request)
    {
        $userId = $request->input('id');
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
