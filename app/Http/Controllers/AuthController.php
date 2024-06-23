<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    public function GoogleAuth(Request $request)
    {
        $throttleKey = $this->throttleKey();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
        return response()->json('limit');
        }

        $user = User::where('email', $request['email'])->first();

        if (!$user) {
        RateLimiter::hit($throttleKey);
        return response()->json('unauthorized');
        }

        Auth::login($user);

        RateLimiter::clear($throttleKey);

        return response()->json('success');
        }

}
