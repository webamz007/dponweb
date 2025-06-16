<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Bid;
use App\Models\Market;
use App\Models\Passbook;
use App\Models\Result;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;

class ResultController extends Controller
{
    public function index(Builder $builder) {
        return $this->results('other', $builder);
    }
    public function starlineResults(Builder $builder) {
        return $this->results('starline', $builder);
    }
    public function results($market_type, $builder) {
        if (request()->ajax()) {
            $today = Carbon::now('Europe/London')->format('Y-m-d');
            $query = Result::query()
                ->select(
                    'results.id',
                    'results.result_date',
                    'markets.name as market_name',
                    'results.session',
                    'results.digit1',
                    'results.digit2',
                    'results.digit3',
                    'results.result',
                )
                ->whereDate('result_date', $today)
                ->join('markets', 'results.market_id', 'markets.id')
                ->orderBy('results.id', 'DESC');
            if ($market_type == 'starline') {
                $query->where('markets.market_type', $market_type);
            } else {
                $query->where('markets.market_type', 'other');
            }
            return DataTables::of($query->get())
                ->editColumn('result_date', function ($row){
                    return Carbon::parse($row->result_date)->format('d-m-Y');
                })
                ->editColumn('session', function ($row){
                    $status = $row->session;
                    if ($status == 'close') {
                        $html = '<span class="badge badge-danger">'.$status.'</span>';
                    } else {
                        $html = '<span class="badge badge-success">'.$status.'</span>';
                    }
                    return $html;
                })
                ->addColumn('winners', function ($row){
                    $html = '<a href="'.action('Admin\ResultController@winners', ['id' => $row->id, 'status' => $row->status]).'" class="btn btn-sm btn-primary">Winners</a>';
                    return $html;
                })
                ->addColumn('action', function ($row){
                    $html = '';
                    $html .= '<a href="'.action('Admin\ResultController@resultDelete', ['id' => $row->id]).'" class="btn btn-sm btn-danger result-delete mr-2">Delete</a>';
                    $html .= '<a href="'.action('Admin\ResultController@reverseResult', ['id' => $row->id]).'" class="btn btn-sm btn-danger result-reverse">
                                    <span class="button-text">Reverse</span>
                                    <span class="loading-text d-none">Loading...</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                              </a>';
                    return $html;
                })
                ->rawColumns(['session', 'winners', 'action'])
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'result_date', 'name' => 'results.result_date', 'title' => 'Date'],
            ['data' => 'market_name', 'name' => 'markets.name', 'title' => 'Market'],
            ['data' => 'session', 'name' => 'results.session', 'title' => 'Session'],
            ['data' => 'digit1', 'name' => 'results.digit1', 'title' => 'digit 1'],
            ['data' => 'digit2', 'name' => 'results.digit2', 'title' => 'digit 2'],
            ['data' => 'digit3', 'name' => 'results.digit3', 'title' => 'digit 3'],
            ['data' => 'result', 'name' => 'results.result', 'title' => 'Result'],
            ['data' => 'winners', 'searchable' => false, 'title' => 'Winners'],
            ['data' => 'action', 'searchable' => false, 'title' => 'Action'],
        ]);
        return view('admin.pages.results.index', compact('html','market_type'));
    }
    public function winners(Builder $builder) {
        $id = request()->id;
        $result = Result::find($id);
        $type = Market::where('id', $result->market_id)->value('market_type');
        if (request()->ajax()) {
            $query = $this->getWinnersBids($id);
            return DataTables::of($query)
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'name', 'name' => 'users.name', 'title' => 'Name'],
            ['data' => 'phone', 'name' => 'users.phone', 'title' => 'Phone'],
            ['data' => 'number', 'searchable' => false, 'title' => 'Number'],
            ['data' => 'amount', 'searchable' => false, 'title' => 'Amount'],
        ]);
        return view('admin.pages.results.winners', compact('html', 'id', 'type'));
    }

    public function getWinnersBids($id, $status = 'pending') {
        $today = Carbon::now()->format('Y-m-d');
        //$today = Carbon::createFromFormat('d-m-Y', '24-05-2025')->format('Y-m-d');

        $result_data = Result::find($id);
        $market_type = Market::where('id', $result_data->market_id)->value('market_type');
        $session = $result_data->session;
        $digit1 = $result_data->digit1;
        $digit2 = $result_data->digit2;
        $digit3 = $result_data->digit3;
        $result = $result_data->result;
        $market_id = $result_data->market_id;
        $check = $digit1. $digit2. $digit3;
        $query = Bid::query()
            ->select
            (
                'users.name',
                'users.phone',
                'bids.number',
                'bids.session',
                'bids.amount',
                'bids.id',
                'bids.user_id',
                'bids.bid_type',
                'bids.market_id',
                'markets.market_type',
            )
            ->join('users', 'bids.user_id', 'users.id')
            ->join('markets', 'bids.market_id', 'markets.id')
            ->where('bids.market_id', $market_id)
            ->where('bids.status', 'pending')
//            ->where('bids.session', $session)
            ->whereDate('bids.bid_date', $today)
            ->when($status === 'complete', function ($query) {
                return $query->where('bids.status', 'complete');
            }, function ($query) {
                return $query->where('bids.status', 'pending');
            })
            ->orderBy('bids.id', 'DESC');

            if($market_type == 'delhi')
            {
                $left = $result_data->digit1;
                $right = $result_data->digit2;
                $query->where(function ($q) use ($left, $right, $result) {
                    $q->where('bids.number', $left)
                        ->orWhere('bids.number', $right)
                        ->orWhere('bids.number', $result);
                });
            }
            elseif ($market_type == 'starline' || $market_type == 'other') {
                $query->where(function ($mainQuery) use ($check, $result, $session, $market_type, $today, $market_id) {
                    // First normal OR logic
                    $mainQuery->where(function ($q) use ($check, $result) {
                        $q->where('bids.number', $check)
                            ->orWhere('bids.number', $result);
                    })
                    ->where('bids.session', $session);

                    // Add special close-session logic (inside same group)
                    if ($session == 'close' && $market_type == 'other') {
                        $open_session = Result::where('market_id', $market_id)
                            ->whereDate('result_date', $today)
                            ->where('session', 'open')
                            ->first();

                        if ($open_session) {
                            $close_result = $result;
                            $close_number = $check;
                            $open_number = $open_session->digit1 . $open_session->digit2 . $open_session->digit3;
                            $open_result = $open_session->result;
                            $double_digit = $open_result . $close_result;
                            $get_half1 = $open_number . '-' . $close_result;
                            $get_half2 = $open_result . '-' . $close_number;
                            $get_full = $open_number . '-' . $close_number;

                            $mainQuery->orWhere(function ($q) use ($double_digit, $get_half1, $get_half2, $get_full) {
                                $q->where('bids.number', $double_digit)
                                    ->orWhere('bids.number', $get_half1)
                                    ->orWhere('bids.number', $get_half2)
                                    ->orWhere('bids.number', $get_full);
                            });
                        }
                    }
                });
            }

        return $query;
    }
    public function create() {
        if (isset(request()->market_type) && request()->market_type == 'starline') {
            $market_type = 'starline';
            $markets = Market::query()->select('id', 'name')->where('market_type', $market_type)->get();
        } else {
            $market_type = 'other';
            $markets = Market::query()->select('id', 'name')->where('market_type', $market_type)->get();
        }
        return view('admin.pages.results.create', compact('markets', 'market_type'));
    }
    public function createDelhiResult() {
        $markets = Market::query()->select('id', 'name')->where('market_type', 'delhi')->get();
        return view('admin.pages.results.create-delhi-result', compact('markets'));
    }
    public function listDelhiResults(Builder $builder) {
        if (request()->ajax()) {
            $today = Carbon::now('Europe/London')->format('Y-m-d');
            $query = Result::query()->select(
                'results.id',
                'results.result_date',
                'markets.name as market_name',
                'results.session',
                'results.digit1',
                'results.digit2',
                'results.result',
            )
                ->where('markets.market_type', 'delhi')
                ->whereDate('results.result_date', $today)
                ->join('markets', 'results.market_id', 'markets.id');
            return DataTables::of($query)
                ->editColumn('result_date', function ($row){
                    return Carbon::parse($row->result_date)->format('d-m-Y');
                })
                ->addColumn('winners', function ($row){
                    $html = '<a href="'.action('Admin\ResultController@winners', ['id' => $row->id, 'status' => $row->status]).'" class="btn btn-sm btn-primary">Winners</a>';
                    return $html;
                })
                ->addColumn('action', function ($row){
                    $html = '<a href="'.action('Admin\ResultController@resultDelete', ['id' => $row->id]).'" class="btn btn-sm btn-danger result-delete mr-2">Delete</a>';
                    $html .= '<a href="'.action('Admin\ResultController@reverseResult', ['id' => $row->id]).'" class="btn btn-sm btn-danger result-reverse">
                                    <span class="button-text">Reverse</span>
                                    <span class="loading-text d-none">Loading...</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                              </a>';
                    return $html;
                })
                ->rawColumns(['winners', 'action'])
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'result_date', 'searchable' => false, 'title' => 'Date'],
            ['data' => 'market_name', 'name' => 'markets.name', 'title' => 'Market'],
            ['data' => 'digit1', 'name' => 'digit1', 'title' => 'Andar'],
            ['data' => 'digit2', 'name' => 'digit2', 'title' => 'Bahar'],
            ['data' => 'result', 'name' => 'result', 'title' => 'Result'],
            ['data' => 'winners', 'searchable' => false, 'title' => 'Winners'],
            ['data' => 'action', 'searchable' => false, 'title' => 'Action'],
        ]);
        return view('admin.pages.results.delhi-markets-result', compact('html'));
    }
    public function timeToSeconds($timeString) {
        [$h, $m, $s] = explode(':', $timeString);
        return $h * 3600 + $m * 60 + $s;
    }
    public function storeResult(Request $request) {
        try {
            $day = strtolower(Carbon::now()->format('l'));

            $currentTime = Carbon::now('Asia/Kolkata')->format('H:i:s');
            $currentSeconds = $this->timeToSeconds($currentTime);

            $market_type = $request->market_type;
            $market_id = $request->market;
            $session = $request->status;
            $result_date = $request->date;
            $market = Market::select('markets.market_type', 'market_details.oet', 'market_details.cet', 'market_details.ort')
                ->join('market_details', 'markets.id', 'market_details.market_id')
                ->where('markets.id', $market_id)
                ->where('market_details.day', $day)
                ->first();
            $oet = Carbon::parse($market->oet)->format('H:i:s');
            $cet = Carbon::parse($market->cet)->format('H:i:s');
            $ort = Carbon::parse($market->ort)->format('H:i:s');

            $oetSeconds = $this->timeToSeconds($oet);
            $cetSeconds = $this->timeToSeconds($cet);
            $ortSeconds = $this->timeToSeconds($ort);

            if ($market->market_type == 'other' && $session == 'open') {
                if ($currentSeconds < $oetSeconds) {
                    throw new \Exception("You can't add result for open before OET");
                } elseif ($currentSeconds > $cetSeconds) {
                    throw new \Exception("You can just add result for open before CET");
                }
            } elseif ($market->market_type == 'other' && $session == 'close') {
                // Get current date in Asia/Kolkata timezone
                $currentDate = Carbon::now('Asia/Kolkata')->format('Y-m-d');
                $resultDate = Carbon::parse($result_date)->format('Y-m-d');

                $isCurrentDateGreater = $currentDate > $resultDate;

                if (($currentSeconds < $cetSeconds) &&  ($isCurrentDateGreater == false)) {
                    throw new \Exception("You can't add result for close before CET");
                }
            } elseif ($market->market_type == 'starline') {
                if ($currentSeconds < $ortSeconds) {
                    throw new \Exception("You can't add result before ORT");
                }
            }

            $exists = Result::where('market_id', $market_id)
                ->whereDate('result_date', $result_date)
                ->where('session', $session)
                ->exists();
            if (!$exists) {
                $result = new Result();
                $result->market_id = $market_id;
                $result->digit1 = $request->digit1;
                $result->digit2 = $request->digit2;
                $result->digit3 = $request->digit3;
                $result->result = $request->result;
                $result->session = $session;
                $result->result_date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
                $result->save();
                $output = ['success' => true, 'msg' => 'Result Added!'];
            } else {
                $output = ['success' => false, 'msg' => 'Result Already Exists!'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }

        if ($market_type == 'starline') {
            return redirect()->route('star.results')->with('status', $output);
        } else {
            return redirect()->route('results')->with('status', $output);
        }
    }
    public function storeDelhiResult(Request $request) {
        try {

            $day = strtolower(Carbon::now()->format('l'));
            $currentTime = Carbon::now('Asia/Kolkata')->format('H:i:s');
            $currentSeconds = $this->timeToSeconds($currentTime);
            $market_id = $request->market;
            $market = Market::select('markets.market_type', 'market_details.ort')
                ->join('market_details', 'markets.id', 'market_details.market_id')
                ->where('markets.id', $market_id)
                ->where('market_details.day', $day)
                ->first();
            $ort = Carbon::parse($market->ort)->format('H:i:s');
            $ortSeconds = $this->timeToSeconds($ort);
            if ($market->market_type == 'delhi') {
                if ($currentSeconds < $ortSeconds) {
                    throw new \Exception("You can't add result before BET");
                }
            }

            $exists = Result::where('market_id', $market_id)
                ->whereDate('result_date', $request->date)
                ->where('session', 'close')
                ->exists();
            if (!$exists) {
                $result = new Result();
                $result->market_id = $market_id;
                $result->digit1 = $request->digit1;
                $result->digit2 = $request->digit2;
                $result->result = $request->result;
                $result->session = 'close';
                $result->result_date = Carbon::parse($request->date)->format('Y-m-d H:i:s');
                $result->save();
                $output = ['success' => true, 'msg' => 'Result Added!'];
            } else {
                $output = ['success' => false, 'msg' => 'Result Already Exists!'];
                return redirect()->route('result.delhi.list')->with('status', $output);
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('result.delhi.list')->with('status', $output);
    }
    public function resultDelete(Request $request) {
        try {
            $delete = Result::where('id', $request->id)->delete();
            if ($delete) {
                $output = ['success' => true, 'msg' => 'Result Deleted!'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong!'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return $output;
    }
    public function payToAll(Request $request) {
        try {
            DB::beginTransaction();

            $result_id = $request->id;
            $bids = $this->getWinnersBids($result_id);

            if (!$bids->first()) {
                throw new \ErrorException('Winners Not Found Against This Result');
            }

            $today = Carbon::now()->format('Y-m-d H:i:s');
            $dataToInsert = [];

            // Generate a UUID (Universally Unique Identifier)
            $uuid = Str::uuid();

            $bids->each(function ($bid) use (&$dataToInsert, $today, $result_id, $uuid) {
                $spent_bid_point = $bid->amount;
                $user_id = $bid->user_id;
                $number = $bid->number;
                $session = $bid->session;
                $market_id = $bid->market_id;

                Bid::where('id', $bid->id)->update(['status' => 'complete']);
                $winning_points = User::getBidPoints($market_id, $spent_bid_point, $bid->bid_type);
                $current_points = User::where('id', $user_id)->value('points');
                User::where('id', $user_id)->update(['points' => DB::raw("points + $winning_points")]);

                $passbookData = [
                    'user_id' => $user_id,
                    'market_id' => $market_id,
                    'bid_id' => $bid->id,
                    'result_id' => $result_id,
                    'token' => $uuid,
                    'number' => $number,
                    'play_points' => $spent_bid_point,
                    'winning_points' => $winning_points,
                    'total_points' => $winning_points + $current_points,
                    'session' => $session,
                    'transaction_type' => 'win',
                    'passbook_date' => $today,
                ];

                $dataToInsert[] = $passbookData;
            });

            // Insert data in bulk
            if (!empty($dataToInsert)) {
                DB::table('passbooks')->insert($dataToInsert);
            }

            DB::commit();
            $output = ['success' => true, 'msg' => 'Transaction Success'];
        } catch (\Exception $e) {
            DB::rollback();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        if ($request->type == 'delhi') {
            return redirect()->route('result.delhi.list')->with('status', $output);
        } elseif($request->type == 'starline') {
            return redirect()->route('star.results')->with('status', $output);
        } else {
            return redirect()->route('results')->with('status', $output);
        }
    }

    public function reverseResult(Request $request) {
        try {
            DB::beginTransaction();
            $result_id = $request->id;
            $passbooks = Passbook::where('result_id', $result_id)->where('transaction_type', 'win');

            if (!$passbooks->exists()) {
                throw new \ErrorException('Winners Not Found Against This Result');
            }

            $passbooks = $passbooks->get();
            $passbooks->each(function ($passbook) use ($result_id) {

                $winning_points = $passbook->winning_points;
                $current_points = User::where('id', $passbook->user_id)->value('points');
                $total_points = $current_points - $winning_points;
                User::where('id', $passbook->user_id)->update(['points' => $total_points]);
                Bid::where('id', $passbook->bid_id)->update(['status' => 'pending']);
                // Delete Passbook Record
                Passbook::where('id', $passbook->id)
                    ->delete();
                // Delete Result Record
                Result::where('id', $result_id)->delete();
            });
            DB::commit();
            $output = ['success' => true, 'msg' => 'Record Reversed Successfully.'];
        } catch (\Exception $e) {
            DB::rollback();
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return $output;
    }
}
