<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBlockedStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated
        if (Auth::check()) {
            $user = Auth::user();

            // Check if the user is blocked (assuming 'blocked' is a boolean column in the users table)
            if ($user->block_status == 1) {
                Auth::logout(); // Log out the user
                return redirect()->route('login')->with('status', ['success' => false, 'msg' => 'Your account has been blocked by the admin.']);
            }
        }

        return $next($request);
    }
}
