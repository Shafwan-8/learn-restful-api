<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request){

        $tervalidasi = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password))
        {
            $token = $user->createToken('Token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'success',
                'token' => $token,
            ], 200);
        }
        else
        {
            throw ValidationException::withMessages([
                'Failed' => ['User not found or Credentials not valid!'],
            ]);
        }

    }

    public function logout(Request $request){
        
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => 'Token has been deleted!'
        ], 200);

    }


}
