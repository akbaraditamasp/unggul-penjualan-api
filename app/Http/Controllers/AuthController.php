<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function login(Request $request)
    {
        [
            "email" => $email,
            "password" => $password
        ] = $request->validate([
            "email" => 'required|email:rfc,dns',
            "password" => 'required'
        ]);

        $user = User::where('users.email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                "error" => "Invalid email or password"
            ], 401);
        }

        return response()->json([
            ...$user->toArray(),
            "token" => $user->createToken("APP Token")->plainTextToken
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json($request->user()->toArray(), 200);
    }

    public function checkToken(Request $request)
    {
        return response()->json($request->user()->toArray(), 200);
    }
}
