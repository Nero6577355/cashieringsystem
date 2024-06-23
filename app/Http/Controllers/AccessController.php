<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccessController extends Controller
{
    public function showTable()
    {
        $user = Auth::user();
        if ($user) {
            $role = $user->roles; 
            if ($role !== 'cashier') {
                return view('table', ['role' => $role]);
            } else {
                $message = 'You cannot view this page.';
                return view('table')->with('message', $message);
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function showRegister()
    {
        $user = Auth::user();
        if ($user) {
            $role = $user->roles; 
            if ($role !== 'cashier') {
                return view('register', ['role' => $role]);
            } else {
                return redirect()->route('home')->with('message', 'You cannot view this page');
            }
        } else {
            return redirect()->route('home');
        }
    }
}
