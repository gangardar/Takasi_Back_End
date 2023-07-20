<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bike;
use App\Models\Driver;

class BikeController extends Controller
{
    function getBike(Request $request){
        $id = $request-> query('id');

        if($id){
            return Bike::all();
        }else{
            return Bike::find($id);
        }



    }

    function store(Request $request){

        $validateData = $request -> validate([
            'driver_id' => 'nullable',
            'manufacturer' => 'reqired| string',
            'model' => 'required| string',
            'year' => 'required|min:1900|max:'.date("Y"),
            'lincencePlateNumber'=> 'required|string',
            'lincenceDocument' => 'image|mimes:jpeg,png,jpg|max:2048',
            'frontPic' => 'image|mimes:jpeg,png,jpg|max:2048',
            'rightPic' => 'image|mimes:jpeg,png,jpg|max:2048',
            'backPic'  => 'image|mimes:jpeg,png,jpg|max:2048',
            'leftPic' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('lincenceDocument'&& 'frontPic' && 'rightPic' && 'backPic' && 'leftPic' )) {
            $lincenceDocument = $request->file('lincenceDocument');
            $photoPath = $lincenceDocument->store('lincence-document','public');
            $url = url('/storage/'.$photoPath);
            $validateData['lincenceDocument'] = $url;

            $frontPic = $request->file('frontPic');
            $photoPath = $frontPic->store('bike-images-front','public');
            $url = url('/storage/'.$photoPath);
            $validateData['frontPic'] = $url;

            $rightPic = $request->file('rightPic');
            $photoPath = $rightPic->store('bike-image-right','public');
            $url = url('/storage/'.$photoPath);
            $validateData['rightPic'] = $url;

            $backPic = $request->file('backPic');
            $photoPath = $backPic->store('bike-image-back','public');
            $url = url('/storage/'.$photoPath);
            $validateData['backPic'] = $url;

            $leftPic = $request->file('leftPic');
            $photoPath = $leftPic->store('bike-image-left','public');
            $url = url('/storage/'.$photoPath);
            $validateData['leftPic'] = $url;

        }

        $validateData['driver_id'] = Driver::where('created_at', $driver->created_at)->first()->id;

        $bike = Bike::create($validateData);

        return response()->json([
            'message' => 'Bike created successfully',
            'passenger' => $bike
        ], 201,[], JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT);
    }
}
