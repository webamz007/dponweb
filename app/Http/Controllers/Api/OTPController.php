<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OTPController extends Controller
{
    public function sendCallOTP(Request $request)
    {
        $apiKey = config('services.2factor.key');
        $phone = $request->phone;

        try {
            $response = Http::get("https://2factor.in/API/V1/{$apiKey}/SMS/{$phone}/AUTOGEN");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'Status' => 'Error',
                'Details' => $e->getMessage()
            ], 500);
        }
    }

    public function verifyOTP(Request $request)
    {
        $apiKey = config('services.2factor.key');
        $sessionId = $request->session_id;
        $otp = $request->otp;

        try {
            $response = Http::get("https://2factor.in/API/V1/{$apiKey}/SMS/VERIFY/{$sessionId}/{$otp}");
            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json([
                'Status' => 'Error',
                'Details' => $e->getMessage()
            ], 500);
        }
    }
}
