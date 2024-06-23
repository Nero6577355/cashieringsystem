<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected function redirectTo()
{
    if (auth()->user()->roles === 'manager') {
        return '/home';
    } elseif (auth()->user()->roles === 'cashier') {
        return '/cashier';
    } else {
        return '/login'; // Default redirect
    }
}


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
  

    public function ajaxLogin(Request $request)
    {
        $attemptsLeft = 3 - RateLimiter::attempts($request->ip());

        if (RateLimiter::tooManyAttempts($request->ip(), 3)) {
            return response()->json(['success' => false, 'message' => 'Too many login attempts. Please try again later.', 'attemptsLeft' => $attemptsLeft], 429);
        }

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($request->ip());
            return response()->json(['success' => true]);
        }

        if (User::where('email', $request->email)->exists()) {
            RateLimiter::hit($request->ip() . '_invalid_password');
            $attemptsLeft = 3 - RateLimiter::attempts($request->ip() . '_invalid_password');
            if (RateLimiter::tooManyAttempts($request->ip() . '_invalid_password', 3)) {
                return response()->json(['success' => false, 'message' => 'Too many login attempts. Please try again later.', 'attemptsLeft' => $attemptsLeft], 429);
            }
            return response()->json(['success' => false, 'message' => 'Invalid password', 'attemptsLeft' => $attemptsLeft], 401);
        }

        RateLimiter::hit($request->ip());

        return response()->json(['success' => false, 'message' => 'Invalid credentials', 'attemptsLeft' => $attemptsLeft], 401);
    }

    public function verify_login_email(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
    
            if ($user) {
                Auth::login($user);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful'
                ]);
                
            } else {
                return response()->json(['status' => 'errorText', 'message' => 'Unauthorized Access!'], 401);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'errorText', 'message' => $e->getMessage()], 500);
        }
    }    
}
