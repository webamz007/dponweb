<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\MarketDetail;
use App\Models\RatioSetting;
use App\Models\Result;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MarketController extends Controller
{
    public function getMarket(Request $request)
    {
        $day = $request->input('day');
        $phone = $request->input('phone');
        $market_type = $request->input('market_type');
        $content = Helpers::getMarketData($day, $phone, $market_type);
        return response()->json($content);
    }

    public function rates()
    {
        try {
            $otherMarket = RatioSetting::select('single_digit', 'double_digit', 'single_pana', 'double_pana', 'tripple_pana', 'half_sangum', 'full_sangum')->where('market_type', 'other')
                ->first();
            $starlineMarket = RatioSetting::select('single_digit', 'single_pana', 'double_pana', 'tripple_pana')->where('market_type', 'starline')
                ->first();
            $delhiMarket = RatioSetting::select('ander', 'baher', 'jodi')->where('market_type', 'delhi')
                ->first();

            $markets = [
                'other' => $otherMarket->toArray(),
                'starline' => $starlineMarket->toArray(),
                'delhi' => $delhiMarket->toArray(),
            ];

            $output = [ 'data' => $markets];
        } catch (\Exception $e) {
            // Log the exception.
            Log::error($e->getMessage());
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return response()->json($output);
    }

}
