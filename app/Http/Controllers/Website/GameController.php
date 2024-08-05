<?php

namespace App\Http\Controllers\Website;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Bid;
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
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class GameController extends Controller
{
    public function view(Request $request) {
        $game_type = $request->type;
        $markets = Market::select('id', 'name')->where('market_type', 'other')->get();
        $market_data = null;
        if ($request->market) {
            $market_data = Market::select('id', 'name')->where('id', $request->market)->first();
        }

        return Inertia::render('Website/Game', [
            'markets' => $markets,
            'bid_type' => $game_type,
            'market_data' => $market_data
        ]);
    }
    public function userPoints() {
        $id = Auth::user()->id;
        return response()->json(['points' => User::where('id', $id)->value('points')]);
    }
    public function checkMarket() {
        $market_id = request()->market_id;
        $day = strtolower(Carbon::now()->format('l'));
        $date = Carbon::now()->format("Y-m-d");
        $data = [];
        $data['result'] = Helpers::get_result($date, $market_id);
        $marketDetails = MarketDetail::where('market_id', '=', $market_id)
            ->where('day', '=', $day)
            ->first();

        $data['oet'] = Carbon::parse($marketDetails->oet)->format('h:i A');
        $data['cet'] = Carbon::parse($marketDetails->cet)->format('h:i A');
        $data['crt'] = Carbon::parse($marketDetails->crt)->format('h:i A');
        $data['ort'] = Carbon::parse($marketDetails->ort)->format('h:i A');
        $data['market_type'] = Market::where('id', $market_id)->value('market_type');
        $data['status'] = $marketDetails->status;
        return response()->json(['market' => $data]);
    }
    public function panelChart(Request $request) {
        $id = $request->id;
        $market_name = Market::where('id', $id)->value('name');


        // Calculate the date range
        $startDate = Carbon::parse('2022-10-24'); // Replace with your desired start date
        $endDate = Carbon::now()->format('Y-m-d'); // Replace with your desired end date

// Fetch the data in weekly batches
        $weeklyData = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $nextDate = $currentDate->copy()->addWeek();

            // Fetch the data for the current week
            $data = Result::where('market_id', $id)
                ->whereBetween(DB::raw("result_date"), [$currentDate, $nextDate->subDay()])
                ->get();
            // Group the data by day of the week
            $key = $currentDate->format('Y-m-d') .' to '.$nextDate->format('Y-m-d');
            $weeklyData[$key] = [];
            // Initialize empty arrays for each day of the week
            for ($dayOfWeek = 1; $dayOfWeek <= 7; $dayOfWeek++) {
                $weeklyData[$key][$dayOfWeek] = [];
            }

            // Fill the data for the corresponding day of the week
            foreach ($data as $row) {
                $date = Carbon::parse($row->date);
                $dayOfWeek = $date->format('N');
                $weeklyData[$key][$dayOfWeek][] = $row;
            }
//            $key = $currentDate->format('Y-m-d') .' to '.$nextDate->format('Y-m-d');
//            $weeklyData[$key] = $data;
            $currentDate = $currentDate->copy()->addWeek();
        }

// Access the weekly data
//        foreach ($weeklyData as $weekStartDate => $data) {
//            // Access the data for each week
//            foreach ($data as $row) {
//                // Access row attributes like $row->attribute_name
//            }
//        }
        return Inertia::render('Website/PanelChart', [
                "chart_data" => $weeklyData,
                "market_name" => $market_name,
            ]);
    }
    public function showStarlineGames() {
        $setting = RatioSetting::select('single_digit', 'double_digit', 'single_pana', 'double_pana', 'tripple_pana', 'half_sangum', 'full_sangum')->where('market_type', 'starline')->first();
        if (Auth::check()) {
            $phone = Auth::user()->phone;
        } else {
            $phone = 'guest';
        }
        $day = strtolower(Carbon::now()->format('l'));
        $market_data = Helpers::getMarketData($day, $phone, 'starline');
        $slides = Slide::where('type', 'starline')->get();
        $notice_board = NoticeBoard::select('title', 'content')->where('market_type', 'starline')->first();
        return Inertia::render('Website/StarlineGames',[
            'markets' => $market_data,
            'setting' => $setting,
            'slides' => $slides,
            'noticeBoard' => $notice_board,
        ]);
    }
    public function showDelhiGames() {
        $setting = RatioSetting::select('ander', 'baher')->where('market_type', 'delhi')->first();
        if (Auth::check()) {
            $phone = Auth::user()->phone;
        } else {
            $phone = 'guest';
        }
        $day = strtolower(Carbon::now()->format('l'));
        $market_data = Helpers::getMarketData($day, $phone, 'delhi');
        $slides = Slide::where('type', 'delhi')->get();
        $notice_board = NoticeBoard::select('title', 'content')->where('market_type', 'delhi')->first();
        return Inertia::render('Website/DelhiGames',[
            'markets' => $market_data,
            'setting' => $setting,
            'slides' => $slides,
            'noticeBoard' => $notice_board,
        ]);
    }
    public function howToPlay() {
        return Inertia::render('Website/HowToPlay');
    }
}
