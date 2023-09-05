<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PassengerController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\BikeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;

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

Route::middleware('auth:sanctum')->get('passenger', [PassengerController::class, 'getPassenger']);
Route::post('passenger/register', [PassengerController::class, 'store']);
Route::middleware('auth:sanctum')->post("passenger/disable",[PassengerController::class, 'disableUser']);
Route::post('passenger/login',[PassengerController::class, 'login']);
Route::delete('passenger/destroy',[PassengerController::class, 'destroy']);
Route::middleware('auth:sanctum')->post("passenger/update",[PassengerController::class, 'update']);
Route::middleware('auth:sanctum')->post('passenger/updateLocation',[PassengerController::class, 'locationUpdate']);


Route::middleware('auth:sanctum')->post('all/auth',[AuthController::class, 'getPassengerByToken']);
Route::middleware('auth:sanctum')->post('all/logout',[AuthController::class,'logout']);




Route::middleware('auth:sanctum')->get("driver",[DriverController::class,'getDriver']);
Route::post("driver/register",[DriverController::class,'store']);
Route::middleware('auth:sanctum')->post("driver/disable",[PassengerController::class, 'disableUser']);
Route::post("driver/login",[DriverController::class,'login']);

Route::get("bike",[BikeController::class,'getBike']);
Route::post("bike",[BikeController::class,'store']);

Route::post('admin/register',[AdminController::class, 'store']);
Route::post('admin/login',[AdminController::class, 'login']);




