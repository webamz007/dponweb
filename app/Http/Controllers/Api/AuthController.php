<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $phone = $request->input('phone');
        $password = $request->input('password');
        $user = User::where('phone', $phone)->where('password', $password)->first();

        if (!$user) {
            return response()->json(['success' => false, 'msg' => 'Invalid credentials.']);
        }

        // Check if the user is blocked
        if ($user->block_status == 1) {
            return response()->json(['success' => false, 'msg' => 'User is blocked']);
        }

        // Check if the user is a duplicate
        $usersWithSamePhone = User::where('phone', $phone)->count();
        if ($usersWithSamePhone > 1) {
            return response()->json(['success' => false, 'msg' => 'Phone number exists multiple time']);
        }
        return response()->json(['success' => true, 'msg' => 'Logged In Success.', 'data' => [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]]);
    }

    function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email|max:255',
                'password' => 'required|string|min:8|max:255',
                'phone' => 'required|unique:users,phone|max:15',
                'refer' => 'nullable|string|max:255',
                'token' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => $validator->errors()], 400);
            }

            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = $request->input('password');
            $user->phone = $request->input('phone');
            $user->refer_from = $request->input('refer') ?? '';
            $user->points = 0;
            $user->token = '';
            $user->refercode = '';
            $user->bank = '';
            $user->upi = '';
            $user->ifsc = '';
            $user->save();
            return response()->json(['success' => true, 'msg' => 'Registered Successfully']);
        } catch (\Exception $e) {
            $error = $e->getMessage();
            Log::error($error);
            return response()->json(['success' => false, 'msg' => $error], 500);
        }
    }




}
