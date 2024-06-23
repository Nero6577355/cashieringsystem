<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // Import Hash facade
use App\Models\AddCashier;
use App\Models\User;

class CashierController extends Controller
{
    public function create()
    {
        return view('cashiers.create');
    }
    public function showRegistrationForm()
    {
        return view('pages.register');
    }
    public function store(Request $request)
{
    // Validate the OTP
    $request->validate([
        'otp' => 'required|in:' . $request->session()->get('otp'),
    ], [
        'otp.in' => 'Invalid OTP. Please try again.',
    ]);

    // Validate the cashier data
    $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',    
        'email' => 'required|string|email|unique:addcashier',
        'password' => ['required', 'string', 'min:8', 'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'],
    ], [
        'password.regex' => 'The password must contain at least one capital letter, one small letter, one number, one special character, and have a minimum of 8 characters.'
    ]);

    // Create the cashier in the addcashier table
    $fullName = trim("{$validatedData['first_name']} {$validatedData['middle_name']} {$validatedData['last_name']}");
    $cashier = AddCashier::create([
        'first_name' => $validatedData['first_name'],
        'middle_name' => $validatedData['middle_name'],
        'last_name' => $validatedData['last_name'],
        'name' => $fullName,
        'email' => $validatedData['email'],
        'password' => Hash::make($validatedData['password']),
        // other fields as necessary
    ]);

    // Automatically create a corresponding user in the users table
    User::create([
        'first_name' => $cashier->first_name,
        'middle_name' => $cashier->middle_name,
        'last_name' => $cashier->last_name,
        'name' => $cashier->name,
        'email' => $cashier->email,
        'password' => $cashier->password,
        'role' => 'cashier', // Assuming 'cashier' is a role in your application
    ]);

    return redirect()->back()->with('success', 'Cashier added successfully.');
}

    public function checkEmail(Request $request)
    {
        $email = $request->input('email');

        $cashier = AddCashier::where('email', $email)->first();

        if ($cashier) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }
}
