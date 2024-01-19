<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
 
        if (! $token = auth('api')->attempt($validator->validated())) {
            return response()->json(['error' => 'username or password is not correct'], 401);
        }
        return $this->createNewToken($token);
    }
    public function refresh() {
        return $this->createNewToken(auth('api')->refresh());
        
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
protected function createNewToken($token)
{
    $user = auth('api')->user();
    $user->makeHidden(['id','created_at', 'updated_at']);
    return response()->json([
        'message' => 'User successfully signed in',
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth('api')->factory()->getTTL() * 60 * 24, // Increase the time by multiplying with 24 (for 24 hours)
        'user' => auth('api')->user()
    ]);
}  
}
