<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Passbook;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\WithdrawalSetting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    public function gameTransaction() {

        return Inertia::render('Website/Transaction');
    }
    public function gameTransactionData() {
        if (request()->ajax()) {
            $user_id = Auth::user()->id;
            $query = Transaction::select('transaction_type', 'transaction_creator', 'points', 'transaction_date')
                ->orderBy('id', 'DESC')
                ->where('user_id', $user_id)->get();
            return DataTables::of($query)
                ->editColumn('transaction_type', function ($row) {
                    $transaction_type = $row->transaction_type;
                    if ($transaction_type == 'deposit') {
                        $html = '<span class="text-green-600">'.ucfirst($transaction_type).'</span>';
                    } else {
                        $html = '<span class="text-red-600">'.ucfirst($transaction_type).'</span>';
                    }
                    return  $html;
                })
                ->editColumn('transaction_creator', function ($row) {
                    $transaction_creator = $row->transaction_creator;
                    if ($transaction_creator == 'admin') {
                        $html = '<span class="text-green-600">'.ucfirst($transaction_creator).'</span>';
                    } else {
                        $html = '<span class="text-red-600">'.ucfirst($transaction_creator).'</span>';
                    }
                    return  $html;
                })
                ->editColumn('transaction_date', function ($row) {
                    return Carbon::parse($row->transaction_date)->format('d-m-Y h:i:s A');
                })
                ->setRowClass(function ($user) {
                    return  'bg-rk-gradient-yellow border-b border-yellow-400 text-white';
                })
                ->rawColumns(['transaction_type', 'transaction_creator'])
                ->make(true);
        }
    }
    public function gameHistory() {
        $type = request()->type;
        if (empty($type)) {
            $type = 'other';
        }
        return Inertia::render('Website/History', [
            'market_type' => $type
        ]);
    }
    public function gameHistoryData() {
        if (request()->ajax()) {
            $user_id = Auth::id();
            $query = Bid::select('markets.name as market_name', 'session', 'number', 'amount', 'bid_date')
                ->join('markets', 'bids.market_id', 'markets.id')
                ->orderBy('bids.id', 'DESC')
                ->where('markets.market_type', request()->type)
                ->where('bids.user_id', $user_id)
                ->get();
            return DataTables::of($query)
                ->editColumn('bid_date', function ($row) {
                    return Carbon::parse($row->bid_date)->format('d-m-Y h:i:s A');
                })
                ->setRowClass(function ($user) {
                    return  'bg-rk-gradient-yellow border-b border-yellow-400 text-white';
                })
                ->make(true);
        }
    }
    public function gamePassbook() {
        return Inertia::render('Website/Passbook');
    }
    public function gamePassbookData() {
        if (request()->ajax()) {
            $user_id = Auth::user()->id;
            $query = Passbook::select('markets.name as market_name', 'number', 'play_points', 'winning_points', 'total_points', 'session', 'passbook_date', 'transaction_type')
                ->join('markets', 'passbooks.market_id', 'markets.id')
                ->where('passbooks.user_id', $user_id)
                ->where('passbooks.transaction_type', 'bid_play')
                ->orWhere('passbooks.transaction_type', 'win')
                ->orderBy('passbooks.id', 'DESC')->get();
            $query = Passbook::query()
                ->select(
                    'markets.name as market_name',
                    'passbooks.number',
                    'passbooks.session',
                    'passbooks.token',
                    'passbooks.transaction_type',
                    'passbooks.passbook_date',
                    'latest_passbooks.play_points',
                    'latest_passbooks.winning_points',
                    'passbooks.total_points',
                )
                ->where('passbooks.user_id', $user_id)
                ->join('markets', 'passbooks.market_id', 'markets.id')
                ->groupBy('passbooks.token')
                ->groupBy('passbooks.transaction_type');

// Subquery to get the latest passbooks.id for each group along with play_points and winning_points
            $subquery = DB::table('passbooks')
                ->select(
                    'token',
                    'transaction_type',
                    DB::raw('MAX(passbooks.id) as latest_passbook_id'),
                    DB::raw('SUM(passbooks.play_points) as play_points'),
                    DB::raw('SUM(passbooks.winning_points) as winning_points')
                )
                ->groupBy('token', 'transaction_type');

// Join with the subquery to get the corresponding row
            $result = $query
                ->joinSub($subquery, 'latest_passbooks', function ($join) {
                    $join->on('passbooks.id', '=', 'latest_passbooks.latest_passbook_id');
                })
                ->orderBy('passbooks.id', 'DESC')
                ->get();
            return DataTables::of($result)
                ->addColumn('points', function ($row) {
                    if ($row->transaction_type == 'win') {
                        $html = '<strong class="text-green-600">+ '.$row->winning_points.'</strong>';
                    } else {
                        $html = '<strong class="text-red-600">- '.$row->play_points.'</strong>';
                    }
                    return $html;
                })
                ->addColumn('result', function ($row){
                    if ($row->transaction_type == 'win') {
                        // Concatenate number and play_points for each individual record within the group
                        $individualRecords = DB::table('passbooks')
                            ->select(DB::raw('GROUP_CONCAT(CONCAT(number, " X ", play_points) SEPARATOR ", ") AS individualRecords'))
                            ->where('token', $row->token)
                            ->where('transaction_type', $row->transaction_type)
                            ->first();

                        $individualRecordsString = $individualRecords->individualRecords ?? '';

                        return $individualRecordsString;
                    } else {
                        return '';
                    }
                })
                ->editColumn('transaction_type', function ($row) {
                    if ($row->transaction_type == 'win') {
                        $html = '<span class="text-green-600">Winning</span>';
                    } else {
                        $html = '<span class="text-red-600">Spent</span>';
                    }
                    return $html;
                })
                ->setRowClass(function ($user) {
                    return  'bg-rk-gradient-yellow border-b border-yellow-400 text-white';
                })
                ->rawColumns(['points', 'transaction_type'])
                ->make(true);
        }
    }

    public function addFunds() {
        $setting = Setting::select('isUMoney', 'isPhonePe', 'razorpay_key_id', 'isRazorPay', 'isFastUPI1', 'isFastUPI2')->first();
        return Inertia::render('Website/AddFunds', [
            'settings' => $setting
        ]);
    }

    public function withdrawFunds() {
        $data = [];
        $withdrawal = WithdrawalSetting::first();
        $data['start_time'] = Carbon::parse($withdrawal->start_time)->format('g:i A');
        $data['end_time'] = Carbon::parse($withdrawal->end_time)->format('g:i A');
        $data['days'] = $withdrawal->days_of_week;
        return Inertia::render('Website/WithdrawFunds', [
            'withdrawal_settings' => $data
        ]);
    }

    public function withdrawRequest(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $amount = $request->amount;
            $withdrawal_settings = WithdrawalSetting::first();

            if (!$withdrawal_settings) {
                throw new \Exception("Withdraw Not Active.");
            }

            // Check if current day matches any saved day in the string format
            $currentDay = lcfirst(Carbon::now()->format('l')); // Get the current day as a full text (e.g., "Monday")
            $savedDaysOfWeek = explode(',', $withdrawal_settings->days_of_week);

            if (in_array($currentDay, $savedDaysOfWeek)) {
                throw new \Exception("Withdraw Not Active for Today.");
            }

            // Check if current time is within the specified time range
            $currentTime = Carbon::now();
            $startTime = Carbon::parse($withdrawal_settings->start_time);
            $endTime = Carbon::parse($withdrawal_settings->end_time);

            if ($startTime > $endTime) {
                $endTime->addDay();
            }

            if ($currentTime < $startTime || $currentTime > $endTime) {
                throw new \Exception("Withdraw Not Active at this Time.");
            }

            if ($amount > $user->points) {
                throw new \Exception("You have not enough balance to withdraw.");
            }

            if ($user->bank == '' || $user->ifsc == '') {
                throw new \Exception("Please Update Your Bank Or IFSC.");
            }

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->points = $amount;
            $transaction->transaction_type = 'withdraw';
            $transaction->transaction_creator = 'user';
            $transaction->status = 'pending';
            $transaction->transaction_date = Carbon::now()->format('Y-m-d H:i:s');
            $transaction->save();

            $user->points -= $amount;
            $user->save();

            if ($transaction) {
                $output = ['success' => true, 'msg' => 'Request submit Successfully.'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong'];
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return response()->json($output);
    }


}
