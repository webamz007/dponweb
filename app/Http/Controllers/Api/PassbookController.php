<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Market;
use App\Models\Passbook;
use App\Models\Result;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PassbookController extends Controller
{
    public function getPassbook(Request $request)
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

            $query = Passbook::select('markets.name as market_name', 'play_points', 'total_points', 'passbook_date')
                ->join('markets', 'passbooks.market_id', 'markets.id')
                ->where('passbooks.user_id', $user->id)
                ->where('passbooks.transaction_type', 'bid_play')
                ->orderBy('passbooks.id', 'DESC')->get();
            return response()->json($query);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }

    public function gamePassbookData()
    {
        if (!request()->has('email')) {
            return response()->json(['success' => false, 'msg' => 'Email Required.']);
        }
        $email = request()->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'msg' => 'User Not Found.']);
        }

        // Query to get passbook data
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
                'passbooks.total_points'
            )
            ->where('passbooks.user_id', $user->id)
            ->join('markets', 'passbooks.market_id', '=', 'markets.id')
            ->groupBy('passbooks.token', 'passbooks.transaction_type');

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

        // Transform data for API response
        $responseData = $result->map(function ($row) {
            return [
                'market_name' => $row->market_name,
                'number' => $row->number,
                'session' => $row->session,
                'token' => $row->token,
                'transaction_type' => $row->transaction_type == 'win' ? 'Winning' : 'Spent',
                'passbook_date' => $row->passbook_date,
                'total_points' => $row->total_points,
                'points' => $row->transaction_type == 'win'
                    ? "+{$row->winning_points}"
                    : "-{$row->play_points}",
                'result' => $row->transaction_type == 'win'
                    ? DB::table('passbooks')
                        ->select(DB::raw('GROUP_CONCAT(CONCAT(number, " X ", play_points) SEPARATOR ", ") AS individualRecords'))
                        ->where('token', $row->token)
                        ->where('transaction_type', $row->transaction_type)
                        ->value('individualRecords')
                    : '',
            ];
        });

        return response()->json(['data' => $responseData], 200);
    }


    function passResult($user, $token) {
        $results = DB::table('passbook')
            ->where('user', $user)
            ->where('token', $token)
            ->whereNotNull('result')
            ->select(DB::raw("CONCAT(result, ' X ', points) as result"))
            ->get()
            ->pluck('result')
            ->toArray();
        return json_encode($results);
    }

    public function getResults(Request $request)
    {
        try {
            if ($request->has('date') && $request->has('time')) {
                $date = $request->input('date');
                $time = $request->input('time');

                $results = Result::select()
                    ->whereDate('created_at', $date)
                    ->whereRaw('HOUR(created_at) = ?', [$time])
                    ->get();

                return response()->json($results);
            } else {
                $output = ['success' => false, 'msg' => 'Date and time are required.'];
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return response()->json($output);
    }

}
