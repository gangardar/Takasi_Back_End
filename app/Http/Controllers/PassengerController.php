<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Passenger;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


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

    function store(Request $request){

        $validateData = $request->validate([
            'name' => 'required|string',
            'phone' => 'required|unique:passengers',
            'email' => 'required|email|unique:passengers',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|string|min:8',
            'status' => Rule::in(['inactive','active','searching','riding','arrived']),
            'accountStatus'=> Rule::in(['ideal', 'disabled']),
            'current_latitude' => 'numeric',
            'current_longitude' => 'numeric',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('passenger-photos', 'public');
            $url = url('/storage/'.$photoPath);
    
            $validateData['photo'] = $url;
        }

        if ($validateData){
            $passenger = Passenger::create($validateData);
        }else{
            return response()-> json([
                'status'=> 422,
                'errors'=> $validateData->messages()
            ],422);
        }

        if($passenger){

            return response()->json([
                'message' => 'Passenger created successfully',
                'passenger' => $passenger
            ], 201);
        }else{
            return response()-> json([
                'message' => 'Something Went Wrong',
            ],500);
        }
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

    function authenticate(Request $request){

        $loginValide =  $request->validate([
            'email' => ['required'|'email'],
            'password' =>['required | string']
        ]);

        $email = $request->email;
        $password = $request->password;

        if(Auth::attempt($loginValide)){
            $request->session()->regenerate();
            return response()->json([
                'message' => 'Passenger created successfully',
                'passenger' => $passenger
            ], 201);
        }else{
            return response()-> json([
                'message' => 'Something Went Wrong',
            ],500);
        }
    }

}