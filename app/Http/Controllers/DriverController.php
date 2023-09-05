<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    
    function getDriver(Request $request){
        $id = $request-> query('id');

        if($id){
            return Driver::find($id);
        }else{
            return Driver::all();
        }
    }
    
    function store(Request $request){
        $validateData =  $request->validate([
            'name' => 'required|string',
            'phone' => 'required|unique:drivers',
            'lincenceNo'=>'required|unique:drivers',
            'email' => 'required|email|unique:drivers',
            'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'password' => 'required|string|min:8',
            'status' => Rule::in(['offline','active','looking','riding','arrived']),
            'drivingLincence' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'current_latitude' => 'required|numeric',
            'current_longitude' => 'required|numeric',
        ]);
        
        if ($request->hasFile('drivingLincence')) {
            $photo = $request->file('drivingLincence');
            $photoPath = $photo->store('driver-lincence-photos','public');
            $url = url('/storage/'.$photoPath);
    
            $validateData['drivingLincence'] = $url;
        }

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('driver-photos', 'public');
            $url1 = url('/storage/' . $photoPath);

            $validateData['photo'] = $url1;
        }

        

        if ($validateData) {
            $driver = Driver::create($validateData);
    
            return response()->json([
                'message' => 'Driver created successfully',
                'driver' => $driver
            ], 201);
        } else {
            // Instead of returning $validateData->messages(), return an array with errors
            return response()->json([
                'status' => 422,
                'errors' => 'Validation failed' // Replace this with $validateData->errors() if needed
            ], 422);
        }


    }

    function disableUser(Request $request){
        $id = $request->query('id');

        if($id){
            $passenger =Driver::find($id); 

            if($passenger && $passenger->accountStatus === 'ideal'){
                $passenger->accountStatus = "disabled";
                $passenger->save();
                return response()->json([
                    'message' => 'Your account has been disabled',
                     'passenger' => $passenger
                    ], 201);
            }else{
                return response()->json([
                    'error' => 'Passenger has already been deactivated'
                ], 404);
            }
        }else{
            return response()->json([
                'error' => 'Passenger Unknown Error'
            ], 404);
        }

    }

    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('driver')->attempt($credentials)) {
            // Authentication successful
            $driver = Driver::where('email', $request['email'])->first();
            $token = $driver->createToken('auth_token')->plainTextToken;

            return new JsonResponse(['message' => 'Passenger authenticated', 'token' => $token, 'passenger' => $driver], 200);
    } else {
        // Authentication failed
        return new JsonResponse(['message' => 'Invalid credentials'], 401);
    }
    }
}
