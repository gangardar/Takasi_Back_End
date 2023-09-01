<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Passenger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class PassengerController extends Controller
{
    
    public function getPassenger(Request $request)
{
    $id = $request->query('id');
    
    if ($id) {
        $passenger = Passenger::find($id);

        if ($passenger && $passenger->accountStatus === 'ideal') {
            return $passenger;
        } else {
            return response()->json(['error' => 'Your account has been disabled'], 404);
        }
    } else {
        return Passenger::all();
    }
}

function store(Request $request)
{
    $validateData = $request->validate([
        'name' => 'required|string',
        'phone' => 'required|unique:passengers',
        'email' => 'required|email|unique:passengers',
        'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        'password' => 'required|string|min:8',
        'status' => Rule::in(['inactive', 'active', 'searching', 'riding', 'arrived']),
        'accountStatus' => Rule::in(['ideal', 'disabled']),
        'current_latitude' => 'required|numeric',
        'current_longitude' => 'required|numeric',
    ]);

    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoPath = $photo->store('passenger-photos', 'public');
        $url = url('/storage/' . $photoPath);

        $validateData['photo'] = $url;
    }

    
    if ($validateData) {
        $passenger = Passenger::create($validateData);

        return response()->json([
            'message' => 'Passenger created successfully',
            'passenger' => $passenger
        ], 201);
    } else {
        // Instead of returning $validateData->messages(), return an array with errors
        return response()->json([
            'status' => 422,
            'errors' => $validateData->errors()
        ], 422);
    }
}

public function update(Request $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['error' => 'Passenger not found'], 404);
    }

    $validateData = $request->validate([
        'name' => 'string',
        'phone' => ['unique:passengers,phone,' . $user->id, 'nullable'],
        'email' => ['email', 'unique:passengers,email,' . $user->id, 'nullable'],
        'photo' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        'password' => 'string|min:8|nullable',
        'status' => Rule::in(['inactive', 'active', 'searching', 'riding', 'arrived']),
        'accountStatus' => Rule::in(['ideal', 'disabled']),
        'current_latitude' => 'numeric',
        'current_longitude' => 'numeric',
    ]);

    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $photoPath = $photo->store('passenger-photos', 'public');
        $url = url('/storage/' . $photoPath);

        $validateData['photo'] = $url;
    }

    $user->update($validateData); // Change $passenger to $user

    return response()->json([
        'message' => 'Passenger data updated successfully',
        'passenger' => $user // Change $passenger to $user
    ], 200);
}



    function disableUser(Request $request){
        $id = $request->query('id');

        if($id){
            $passenger =Passenger::find($id); 

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

        if (Auth::guard('passenger')->attempt($credentials)) {
            // Authentication successful
            $passenger = Passenger::where('email', $request['email'])->first();
            $token = $passenger->createToken('auth_token')->plainTextToken;

            return new JsonResponse(['message' => 'Passenger authenticated', 'token' => $token], 200);
    } else {
        // Authentication failed
        return new JsonResponse(['message' => 'Invalid credentials'], 401);
    }


        
    }

    public function destroy(Request $request)
    {
        $id = $request->query('id');
        // Delete the passenger
        if ($id) {
            $passenger = Passenger::find($id);
    
            if ($passenger) {
                $slug = $passenger->slug;
                $passenger->delete();
                return response()->json([
                    'message' => $slug . ' account has been deleted.',
                    'passenger' => $passenger
                ], 201);
            } else {
                return response()->json([
                    'error' => 'Passenger does not exist!'
                ], 404);
            }
        } else {
            return response()->json([
                'error' => 'Passenger Unknown Error'
            ], 404);
        }
    }

    public function locationUpdate(Request $request)
    {
        $validateData = $request->validate([
            'current_latitude' => 'required|numeric',
            'current_longitude' => 'required|numeric',
        ]);


        $userId = $request->user()->id; // Getting the authenticated passenger's user ID
        $id = $request->query('id');
        // Checking if the passenger ID in the request matches the authenticated passenger's ID
        if ($userId !== $id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($validateData) {
            $passenger = Passenger::create($validateData);
    
            return response()->json([
                'message' => $passenger->name .'Passenger Location Updated Successfully!',
                'passenger' => $passenger
            ], 201);
        } else {
            return response()->json([
                'status' => 422,
                'errors' => $validateData->errors()
            ], 422);
        }

        // Update the passenger's location here

        return response()->json(['message' => 'Location updated successfully']);
    }
}
