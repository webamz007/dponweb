<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Market;
use App\Models\MarketDetail;
use App\Models\Passbook;
use App\Models\Result;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WithdrawalSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function getBidHistory(Request $request)
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

            $bids = Bid::select('markets.name as market_name', 'amount', 'number', 'bid_type', 'session', 'status', 'bid_date')
                ->join('markets', 'bids.market_id', 'markets.id')
                ->where('user_id', '=', $user->id)
                ->orderBy('bids.id', 'DESC')
                ->get();

            return response()->json($bids);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function getTransaction(Request $request)
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

            $transactions = Transaction::where('user_id', $user->id)
                ->orderBy('id', 'DESC')
                ->get();

            $data = [];
            if ($transactions) {
                foreach ($transactions as $transaction) {

                    $data[] = [
                        'points' => $transaction->points,
                        'transaction_type' => $transaction->transaction_type,
                        'transaction_creator' => $transaction->transaction_creator,
                        'status' => $transaction->status,
                        'transaction_date' => $transaction->transaction_date,
                    ];

                }
            }

            return response()->json($data);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function withdrawRequest(Request $request) {
        try {
            DB::beginTransaction();

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'points' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'msg' => $validator->errors()]);
            }

            $email = $request->input('email');
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User Not Found.']);
            }

            $points = $request->input('points'); // Input validation

            $withdrawalSettings = WithdrawalSetting::first();

            if (!$withdrawalSettings) {
                throw new \Exception("Withdraw Not Active.");
            }

            // Check if the withdrawal is allowed today
            $currentDay = Carbon::now()->format('l');
            $savedDaysOfWeek = explode(',', $withdrawalSettings->days_of_week);
            if (in_array($currentDay, $savedDaysOfWeek)) {
                throw new \Exception("Withdraw Not Active for Today.");
            }

            // Check if the withdrawal is allowed at this time
            $currentTime = Carbon::now()->format('H:i:s');
            $startTime = $withdrawalSettings->start_time;
            $endTime = $withdrawalSettings->end_time;
            if ($currentTime < $startTime || $currentTime > $endTime) {
                throw new \Exception("Withdraw Not Active at this Time.");
            }

            if ($points > $user->points) {
                throw new \Exception("You have not enough balance to withdraw.");
            }

            if (empty($user->bank) || empty($user->ifsc)) {
                throw new \Exception("Please Update Your Bank Or IFSC.");
            }

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->points = $points;
            $transaction->transaction_type = 'withdraw';
            $transaction->transaction_creator = 'user';
            $transaction->status = 'pending';
            $transaction->transaction_date = now();
            $transaction->save();

            $user->decrement('points', $points); // Decrement user's points

            if ($transaction) {
                $output = ['success' => true, 'msg' => 'Request submit Successfully.'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong.'];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }

        return response()->json($output);
    }

    function insertBid(Request $request)
    {
        try {
            if ($request->has('bids')) {
                $date = Carbon::now()->format('Y-m-d');
                $bids = $request->input('bids');
                DB::beginTransaction();

                $totalPointsRequired = 0;
                foreach ($bids as $bid) {
                    $totalPointsRequired += $bid['amount'];
                }

                // Check if the user has enough points
                $user_id = $bids[0]['user_id']; // Assuming all bids are for the same user
                $currentPoints = User::where('id', $user_id)->value('points');

                if ($currentPoints < $totalPointsRequired) {
                    return response()->json(['success' => false, 'msg' => 'Not enough points']);
                }

                // Generate a UUID (Universally Unique Identifier)
                $uuid = Str::uuid();

                foreach ($bids as $bid) {
                    $amount = $bid['amount'];
                    $market_id = $bid['market_id'];
                    $number = $bid['number'];
                    $session = $bid['session'];
                    $type = $bid['type'];
                    $user_id = $bid['user_id'];

                    $day_name = Carbon::now()->format('l');

                    $select_market = MarketDetail::where('market_id', $market_id)->where('day', $day_name)->first();
                    if (!$select_market) {
                        return response()->json(['success' => false, 'msg' => 'Market Detail Not Found']);
                    }

                    if ($session == "open") {
                        $time = Carbon::parse($select_market->oet)->format('H:i:s');
                    } elseif ($session == "close") {
                        $market = Market::where('id', $market_id)->first();
                        if ($market->market_type == 'delhi') {
                            $time = Carbon::parse($select_market->oet)->format('H:i:s');
                        } else {
                            $time = Carbon::parse($select_market->cet)->format('H:i:s');
                        }
                    }
                    $current_time = Carbon::now()->format('H:i:s');
                    if ($current_time > $time) {
                        return response()->json(['success' => false, 'msg' => 'Time Over']);
                    }

                    $check_result = Result::where('market_id', $market_id)->whereDate('result_date', $date)->where('session', $session)->exists();
                    if ($check_result) {
                        return response()->json(['success' => false, 'msg' => 'Market Result Exists']);
                    }

                    $current_points = User::where('id', $user_id)->value('points');
                    $total = $current_points - $amount;

                    User::where('id', $user_id)->update(['points' => $total]);

                    $bidId = Bid::insertGetId([
                        'user_id' => $user_id,
                        'market_id' => $market_id,
                        'amount' => $amount,
                        'number' => $number,
                        'bid_type' => $type,
                        'session' => $session,
                        'status' => 'pending',
                        'bid_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    ]);

                    $passbookDataToInsert[] = [
                        'user_id' => $user_id,
                        'market_id' => $market_id,
                        'bid_id' => $bidId,
                        'token' => $uuid,
                        'number' => $number,
                        'play_points' => $amount,
                        'total_points' => $total,
                        'session' => $session,
                        'transaction_type' => 'bid_play',
                        'passbook_date' => Carbon::now()->format('Y-m-d H:i:s'),
                    ];
                }

                if (!empty($passbookDataToInsert)) {
                    Passbook::insert($passbookDataToInsert);
                }

                DB::commit();
                return response()->json(['success' => true, 'msg' => 'Bid Inserted Successfully']);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong']);
    }


    function addFunds(Request $request)
    {
        try {

            if (!$request->has('email') && !$request->has('points')) {
                return response()->json(['success' => false, 'msg' => 'Email & Points Are Required.']);
            }

            $email = $request->input('email');
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json(['success' => false, 'msg' => 'User Not Found.']);
            }

            $points = $request->points;
            $name = $request->name;
            $approval_no = $request->approval_no;
            $trans_id = $request->trans_id;
            $token = $request->token;

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->points = $points;
            $transaction->transaction_type = 'deposit';
            $transaction->transaction_creator = 'user';
            $transaction->status = 'complete';
            $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
            $transaction->save();
            if ($transaction) {
                $user->points += $points;
                $user->save();
                if ($user) {
                    $output = ['success' => true, 'msg' => 'Points Updated Successfully.'];
                } else {
                    $output = ['success' => false, 'msg' => 'Something Went Wrong'];
                }
                return response()->json($output);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
        return response()->json(['success' => false, 'msg' => 'Something went wrong']);
    }

}
