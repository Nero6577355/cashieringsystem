<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
        public function authenticate(Request $request)
    {
        $token = $request->input('token'); // Assuming you pass the token from the frontend

        // Log the received token
        \Log::info('Received token:', ['token' => $token]);

        $user = $this->decodeJwtResponse($token);

        if ($user) {
            return response()->json(['message' => 'Authentication successful', 'user' => $user]);
        } else {
            return response()->json(['message' => 'Invalid Google account'], 400);
        }
    }
}
