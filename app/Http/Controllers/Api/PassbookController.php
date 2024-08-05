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
