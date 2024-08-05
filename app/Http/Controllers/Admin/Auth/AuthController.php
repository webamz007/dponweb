<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Display the login view.
     */
    public function create()
    {
        return view('admin.auth.login');
    }
    /**
     * Authenticate the Admin.
     */
    public function store(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
            return redirect()->route('dashboard')->with('status', ['success' => true, 'msg' => 'Logged in successfully.']);
        }

        return back()->with('status', ['success' => false, 'msg' => 'Invalid email or password.']);
    }
    /**
     * Logout the Admin.
     */
    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login')->with('status', ['success' => true, 'msg' => 'Logout successfully.']);
    }
}
