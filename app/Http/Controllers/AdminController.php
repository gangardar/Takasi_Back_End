<?php

namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;


class AdminController extends Controller
{
    
    public function getAdmin(Request $request)
{
    $id = $request->query('id');
    
    if ($id) {
        $admin = Admin::find($id);

        if ($admin) {
            return $admin;
        } else {
            return response()->json(['error' => 'Your account has been disabled'], 404);
        }
    } else {
        return Admin::all();
    }
}

function store(Request $request)
{
    $validateData = $request->validate([
        'name' => 'required|string',
        'phone' => 'required|unique:admins',
        'email' => 'required|email|unique:admins',
        'password' => 'required|string|min:8',
    ]);

    if ($validateData) {
        $admin = Admin::create($validateData);

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin
        ], 201);
    } else {

        return response()->json([
            'status' => 422,
            'errors' => $validateData->errors()
        ], 422);
    }
}


    function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication successful
            $admin = Admin::where('email', $request['email'])->first();
            $token = $admin->createToken('auth_token')->plainTextToken;

            return new JsonResponse(['message' => 'Admin authenticated', 'token' => $token], 200);
    } else {
        // Authentication failed
        return new JsonResponse(['message' => 'Invalid credentials'], 401);
    }


        
    }

}