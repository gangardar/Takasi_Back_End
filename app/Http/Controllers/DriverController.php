<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Driver;
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
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'required|string|min:8',
            'status' => Rule::in(['offline','active','looking','riding','arrived']),
            'drivingLincence' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'current_latitude' => 'numeric',
            'current_longitude' => 'numeric',
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
            $url = url('/storage/' . $photoPath);

            $validateData['photo'] = $url;
        }

        

        $driver = Driver::create($validateData);

        if($driver){
            return response()->json([
                'message' => 'Driver created successfully',
                'passenger' => $driver
            ], 201,[], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
            
        }else{
            return response()-> json([
                'message' => 'Something Went Wrong in Driver',
            ],500);
        }


    }
}
