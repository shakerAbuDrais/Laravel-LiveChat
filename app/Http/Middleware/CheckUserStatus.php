<?php

// app/Http/Middleware/CheckUserStatus.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->status === 'inactive') {
            Auth::logout(); // Log out the user
            return redirect('/login')->with('error', 'Your account is inactive. Please contact Admin to activate it.');
        }

        return $next($request);
    }
}

