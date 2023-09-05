<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function getPassengerByToken(Request $request)
    {
        // Authenticate the passenger using the token in the request headers
        if ($user=auth()->user()) {
            
            $role = $user->role;
            // Passenger is authenticated, you can access the passenger's information
            return response()->json([
                'user' => $user,
            ], 200);
        } else {
            // Passenger is not authenticated or token is invalid
            return response()->json([
                'error' => 'Unauthenticated',
            ], 401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        
        return response()->json([
            'message' => 'Logout Successfully!',
        ], 200);

    }
}
