<?php
namespace App\Helpers;


use App\Models\Bid;
use App\Models\Market;
use App\Models\MarketDetail;
use App\Models\Passbook;
use App\Models\Result;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;

class Helpers {
    public $today;

    public function __construct()
    {
        $this->today = Carbon::now()->format('Y-m-d');
    }

    public static function getTodayPayouts() {
        $today = Carbon::now()->format('Y-m-d');
        $bids = Bid::where('bid_date', $today)->where('status', 'complete')->get();
        $amount = 0;
        foreach ($bids as $bid) {
            $ratio_amount = Setting::value($bid->type);
            $cal_amount = $ratio_amount * $bid->amount;
            $amount += $cal_amount;
        }
        return $amount;
    }
    public static function pass_points($user,$status){
        $passbook = Passbook::query()->where('user', $user)->where('status', $status)->get();
        $amount = 0;
        foreach ($passbook as $pass) {
            if($status == "green"){
                $total = $pass->total;
            }else{
                $total = $pass->points;
            }
            $amount = $amount + $total;
        }
        return $amount;
    }
    public static function getMarketData($day, $phone, $market_type = 'other')
    {
        $markets = Market::where('market_type', '=', $market_type)->get();

        $content = [];
        foreach ($markets as $market) {
            $data = [];

            $id = $market->id;
            $data['id'] = $id;
            $data['name'] = $market->name;
            if ($phone != 'guest') {
                $user = User::where('phone', $phone)->first();
                $data['block_status'] = $user->block_status;
            }
            $date = Carbon::now()->format("Y-m-d");
            $data['result'] = Helpers::get_result($date, $id);

            $marketDetails = MarketDetail::where('market_id', '=', $id)
                ->where('day', '=', $day)
                ->first();

            $data['oet'] = Carbon::parse($marketDetails->oet)->format('h:i A');
            $data['cet'] = Carbon::parse($marketDetails->cet)->format('h:i A');
            $data['crt'] = Carbon::parse($marketDetails->crt)->format('h:i A');
            $data['ort'] = Carbon::parse($marketDetails->ort)->format('h:i A');
            $data['status'] = $marketDetails->status;
            array_push($content, $data);
        }

        return $content;
    }
    public static function get_result($date, $market)
    {
        $result = "";
        $row = Result::where('market_id', $market)
            ->where('result_date', $date)
            ->where('session', 'open')
            ->first();
        $market_type = Market::where('id', $market)->value('market_type');
        if ($market_type && $market_type == 'starline') {
            $starline_result = Result::where('market_id', $market)
                ->where('result_date', $date)
                ->where('session', 'open')
                ->first();
            if (!empty($starline_result)) {
                $result = $starline_result->digit1 . $starline_result->digit2 . $starline_result->digit3 . '-' . $starline_result->result;
            }
        }
        if ($market_type && $market_type == 'delhi') {
            $delhi_result = Result::where('market_id', $market)
                ->where('result_date', $date)
                ->where('session', 'close')
                ->first();
            if (!empty($delhi_result)) {
                $result = $delhi_result->result;
            }
        }
        if (!empty($row)) {
            $value = $row->digit1 . $row->digit2 . $row->digit3 . '-' . $row->result;

            $row2 = Result::where('market_id', $market)
                ->where('result_date', $date)
                ->where('session', 'close')
                ->first();

            if (!empty($row2)) {
                $value .= $row2->result . '-' . $row2->digit1 . $row2->digit2 . $row2->digit3;
            }

            $result = $value;
        }

        return $result;
    }
}
