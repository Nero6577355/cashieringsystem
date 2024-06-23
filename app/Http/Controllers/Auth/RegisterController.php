<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = 'register';

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showRegistrationForm()
    {
        return view('pages.register');
    }
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'otp' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
        ],
            [
                'password.regex' => 'The password must contain at least one capital letter, one small letter, one number, one special character, and have a minimum of 8 characters.'
            ]);
            
    }
    protected function create(array $data)
    {
        // Verify OTP
        $inputOTP = $data['otp'];
        $sessionOTP = session('otp');

        if ($inputOTP != $sessionOTP) {
            return redirect()->back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // If OTP is correct, proceed with user creation
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->back()->with('success', 'User registered successfully.');
    }

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        $user = User::where('email', $email)->first();

        if ($user) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    protected function registered(Request $request, $user)
    {
        if ($request->$user->roles === 'manager') {
            return redirect()->intended($this->redirecTo());
        }
    }
}
