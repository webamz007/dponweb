<?php

namespace App\Http\Controllers\Website;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\MarketDetail;
use App\Models\NoticeBoard;
use App\Models\RatioSetting;
use App\Models\Result;
use App\Models\Setting;
use App\Models\Slide;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index() {
        $setting = RatioSetting::select('single_digit', 'double_digit', 'single_pana', 'double_pana', 'tripple_pana', 'half_sangum', 'full_sangum')->first();
        if (Auth::check()) {
            $phone = Auth::user()->phone;
        } else {
            $phone = 'guest';
        }
        $day = strtolower(Carbon::now()->format('l'));
        $market_data = Helpers::getMarketData($day, $phone);
        $slides = Slide::where('type', 'other')->get();
        $notice_board = NoticeBoard::select('title', 'content')->where('market_type', 'other')->first();
        return Inertia::render('Website/Home', [
            'markets' => $market_data,
            'setting' => $setting,
            'slides' => $slides,
            'noticeBoard' => $notice_board,
        ]);
    }
}
