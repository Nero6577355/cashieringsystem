<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if ($request->user() && $request->user()->roles == $role) {
            if ($role === 'manager') {
                return redirect('/home');
            } elseif ($role === 'cashier') {
                return redirect('/cashier');
            }
        }
    
        // Fallback logic: Redirect to default route or display an error message
        return redirect('/default-route');
    }
    
}
