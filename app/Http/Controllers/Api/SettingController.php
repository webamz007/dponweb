<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NoticeBoard;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function getTime() {
        $date = Carbon::now()->format('g:i a');
        return response()->json(['result' => $date]);
    }

    public function getSettings(Request $request)
    {
        $settings = Setting::first();
        $data = [
            'name' => $settings->name,
            'vpa' => $settings->vpa,
            'isUpi' => $settings->isUpi,
            'isUMoney' => $settings->isUMoney,
            'merchant' => $settings->merchant_key,
            'guessing_app' => $settings->guessing,
            'website' => $settings->website,
            'isRazorpay' => $settings->isRazorpay,
            'razorpay_key' => $settings->razor_key,
            'salt' => $settings->salt_key,
            'version' => $settings->version,
            'url' => $settings->url,
        ];
        return response()->json($data);
    }

    public function changePassword(Request $request)
    {
        try {
            if (!$request->has('phone') && !$request->has('password')) {
                return response()->json(['success' => false, 'msg' => 'Phone & Password Are Required.']);
            }
            $password = $request->input('password');
            $phone = $request->input('phone');

            $user = User::where('phone', $phone)->first();

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User Not Found.']);
            }

            User::where('phone', '=', $phone)->update(['password' => $password]);
            return response()->json(['success' => true, 'msg' => 'Password updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function noticeBoard() {
        try {

            $otherMarket = NoticeBoard::select('title', 'content')->where('market_type', 'other')->first();
            $starlineMarket = NoticeBoard::select('title', 'content')->where('market_type', 'starline')->first();
            $delhiMarket = NoticeBoard::select('title', 'content')->where('market_type', 'delhi')->first();

            $noticeBoards = [
                'other' => $otherMarket ? $otherMarket->toArray() : [],
                'starline' => $starlineMarket ? $starlineMarket->toArray() : [],
                'delhi' => $delhiMarket ? $delhiMarket->toArray() : [],
            ];


            return response()->json(['data' => $noticeBoards]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
}
