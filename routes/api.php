<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\BikeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get("passenger",[PassengerController::class,'getPassenger']);
Route::post("passenger",[PassengerController::class,'store']);
Route::delete("passenger/disable",[PassengerController::class, 'disableUser']);


Route::middleware('auth:sanctum')->get('/passenger', function (Request $request) {
    return $request->passenger();
});

Route::get("driver",[DriverController::class,'getDriver']);
Route::post("driver",[DriverController::class,'store']);

Route::get("bike",[BikeController::class,'getBike']);
Route::post("bike",[BikeController::class,'store']);


