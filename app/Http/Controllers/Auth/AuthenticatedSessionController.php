<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }
    /**
     * Handle an incoming authentication request using Custom Logic.
     */
    public function customLogin(Request $request)
    {
        // Retrieve the credentials from the request
        $credentials = $request->only('email', 'password');

        // Add your custom logic here to validate the credentials without password hashing
        $user = User::where('phone', $credentials['email'])->first();
        // Check if the user is blocked
        if (isset($user->block_status) && $user->block_status == 1) {
            return redirect()->back()->withInput($request->only('email'))->withErrors([
                'email' => 'You are blocked due to unusual activity.',
            ]);
        }
        if ($user && $user->password === $credentials['password']) {
            // Authentication successful
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        return redirect()->back()->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->forget('web');

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
