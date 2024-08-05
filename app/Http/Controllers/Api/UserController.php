<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function myProfile(Request $request)
    {
        try {
            if (!$request->has('email')) {
                return response()->json(['success' => false, 'msg' => 'Email Required.']);
            }

            $email = $request->input('email');
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User Not Found.']);
            }

            if (User::where('phone', $email)->count() > 1) {
                return response()->json(['success' => false, 'msg' => 'Duplicate Record Found.']);
            }

            $settings = Setting::firstOrFail();

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'points' => $user->points,
                'device_id' => $user->device_id,
                'bank' => $user->bank,
                'upi' => $user->ubi,
                'share' => $user->refercode,
                'ifsc' => $user->ifsc,
                'guessing_app' => $settings->guessing,
                'website' => $settings->website,
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    function updateProfile(Request $request){
        try {

            if (!$request->has('email')) {
                return response()->json(['success' => false, 'msg' => 'Email Required.']);
            }

            $email = $request->input('email');
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User Not Found.']);
            }

            $data = $request->only(['name', 'upi', 'bank', 'ifsc']);

            // Remove any null or empty values from the array
            $data = array_filter($data);

            $user->update($data);


            return response()->json(['success' => true, 'msg' => 'Profile updated successfully.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
