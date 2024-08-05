<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Helpers\Helpers;
use App\Models\Bid;
use App\Models\Market;
use App\Models\Passbook;
use App\Models\Result;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function adminDashboard() {
        $today = Carbon::now('Europe/London')->format('Y-m-d');
        $user = User::count();
        $points = User::sum('points');
        $coins = Bid::whereDate('bid_date', $today)->sum('amount');
        $payouts = Passbook::where('transaction_type', 'win')->whereDate('passbook_date', $today)->sum('winning_points');
        $profits = $coins - $payouts;
        $pending_withdraw = Transaction::whereDate('transaction_date', $today)->where('transaction_type', 'withdraw')->where('transaction_creator', 'user')->where('status', 'pending')->sum('points');
        $completed_withdraw = Transaction::whereDate('transaction_date', $today)->where('transaction_type', 'withdraw')->where('transaction_creator', 'user')->where('status', 'complete')->sum('points');
        $today_deposit = Transaction::whereDate('transaction_date', $today)->where('transaction_type', 'deposit')->where('transaction_creator', 'user')->sum('points');
        $admin_deposit = Transaction::whereDate('transaction_date', $today)->where('transaction_type', 'deposit')->where('transaction_creator', 'admin')->sum('points');
        return view('admin.pages.dashboard', compact('user', 'coins', 'points', 'payouts', 'profits', 'pending_withdraw', 'completed_withdraw', 'today_deposit', 'admin_deposit'));
    }
    public function index(Builder $builder) {
        return $this->users(false, $builder);
    }
    public function starPlayers(Builder $builder) {
        return $this->users(true, $builder);
    }
    public function users($star, $builder) {
        if (request()->ajax()) {
            $query = User::query();
            if ($star) {
                $query->where('star_status', true);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('passbook', function ($row){
                    $html = '<a href="'.route('users.passbook', ['user' => $row->id]).'" class="btn btn-sm btn-primary">View Passbook</a>';
                    return $html;
                })
                ->addColumn('bids', function ($row){
                    $html = '<a href="'.route('users.bids', ['user_id' => $row->id]).'" class="btn btn-sm btn-primary">View Bids</a>';
                    return $html;
                })
                ->editColumn('password', function ($row){
                    return $row->password;
                })
                ->addColumn('agents', function ($row){
                    $status = $row->user_type;
                    if ($status == 'agent') {
                        $html = '<a href="'.action('Admin\AgentController@markAgent', ['id' => $row->id, 'type' => $status]).'" class="btn btn-sm btn-danger mark-agent">Remove from Agent</a>';
                    } else {
                        $html = '<a href="'.action('Admin\AgentController@markAgent', ['id' => $row->id, 'type' => $status]).'" class="btn btn-sm btn-primary mark-agent">Make an Agent</a>';
                    }
                    return $html;
                })
                ->addColumn('star_player', function ($row){
                    $status = $row->star_status;
                    if ($status) {
                        $html = '<a href="'.action('Admin\UserController@markStar', ['id' => $row->id, 'type' => $status]).'" class="btn btn-sm btn-danger mark-agent">Remove from Star</a>';
                    } else {
                        $html = '<a href="'.action('Admin\UserController@markStar', ['id' => $row->id, 'type' => $status]).'" class="btn btn-sm btn-primary mark-agent">Add star player</a>';
                    }
                    return $html;
                })
                ->addColumn('block', function ($row){
                    if ($row->block_status) {
                        $checked = 'checked';
                    } else {
                        $checked = '';
                    }
                    $html = '<label class="d-flex justify-content-center">
                                <input data-id="'. $row->id .'" '.$checked.' type="checkbox" value="0" name="custom-switch-checkbox" class="custom-switch-input user-block">
                                <span class="custom-switch-indicator"></span>
                              </label>';
                    return $html;
                })
                ->addColumn('action', function ($row){
                    $html =  '<a href="'.action('Admin\UserController@depositWithdrawPoint', ['id' => $row->id, 'type' => 'deposit']).'" class="btn btn-sm btn-success mb-2 deposit-withdraw-point"><i class="fas fa-plus"></i></a>
                             <a href="'.action('Admin\UserController@depositWithdrawPoint', ['id' => $row->id, 'type' => 'withdraw']).'" class="btn btn-sm btn-danger mb-2 deposit-withdraw-point"><i class="fas fa-minus"></i></a>
                             <a href="'.action('Admin\TransactionController@transactions', ['id' => $row->id]).'" class="btn btn-sm btn-primary mb-2"><i class="fas fa-bars"></i></a>
                             <a href="'.action('Admin\UserController@destroy', ['id' => $row->id]).'" class="btn btn-sm btn-danger mb-2 delete-user"><i class="fas fa-times"></i></a>';
                    return $html;
                })
                ->setRowAttr([
                    'data-href' => function ($row) {
                        return action('Admin\AgentController@markAgent', [$row->id]);
                    }])
                ->rawColumns(['passbook', 'bids', 'agents', 'star_player', 'block', 'action'])
                ->make(true);
        }

        $html = $builder->columns([
            ['data' => 'id', 'name' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'passbook', 'title' => 'Passbook'],
            ['data' => 'bids', 'title' => 'View Bids'],
            ['data' => 'password', 'name' => 'password', 'title' => 'Password'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'points', 'title' => 'Points'],
            ['data' => 'bank', 'name' => 'bank', 'title' => 'Bank'],
            ['data' => 'ifsc', 'name' => 'ifsc', 'title' => 'IFSC'],
            ['data' => 'upi', 'name' => 'upi', 'title' => 'UPI'],
            ['data' => 'agents', 'name' => 'agents', 'title' => 'Agents'],
            ['data' => 'star_player', 'title' => 'Start Player'],
            ['data' => 'block', 'name' => 'block', 'title' => 'Block'],
            ['data' => 'action', 'title' => 'Manage'],
        ]);
        return view('admin.pages.users.index', compact('html', 'star'));

    }
    public function markStar(Request $request) {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $status = $request->type;
                if ($status) {
                    $status = false;
                    $msg = ' has been removed from star player';
                } else {
                    $status = true;
                    $msg = ' has been marked star player';
                }
                $user = User::find($id);
                $user->star_status = $status;
                $user->save();
                if ($user) {
                    $msg = $user->name.$msg;
                    $output = ['success' => true,
                        'msg' => $msg
                    ];
                } else {
                    $output = ['success' => false,
                        'msg' => 'Something went wrong'
                    ];
                }
            } catch (\Exception $e) {
                $output = ['success' => false,
                    'msg' => $e->getMessage()
                ];
            }
            return $output;
        }
    }
    public function depositWithdrawPoint(Request $request) {
        $id = $request->id;
        $type = $request->type;
        $user = User::query()->select('id', 'name', 'phone', 'points')->find($id);
        return view('admin.pages.users.deposit-withdraw-modal', compact('type', 'user'));
    }
    public function depositWithdrawTransaction(Request $request) {
        try {
            $id = $request->id;
            $transaction_type = $request->type;
            $points = $request->points;
            $date = Carbon::now()->format('Y-m-d H:i:s');
            $update_points = User::find($id);
            if ($transaction_type == 'deposit') {
                $update_points->points += $points;
            } else {
                $update_points->points -= $points;
            }
            $update_points->save();
            if ($update_points) {
                $points_transaction = new Transaction();
                $points_transaction->user_id = $id;
                $points_transaction->points = $points;
                $points_transaction->transaction_type = $transaction_type;
                $points_transaction->transaction_creator = 'admin';
                $points_transaction->status = 'complete';
                $points_transaction->transaction_date = $date;
                $points_transaction->save();
                if ($update_points) {
                    $output = ['success' => true, 'msg' => 'Transaction Completed'];
                } else {
                    $output = ['success' => false, 'msg' => 'Something Went Wrong!'];
                }
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return $output;
    }
    public function passbook(Request $request, Builder $builder) {
        if (request()->ajax()) {
            $query = Passbook::query()
                ->select(
                    'users.id',
                    'users.name as user_name',
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
                ->where('passbooks.user_id', $request->user)
                ->join('users', 'passbooks.user_id', 'users.id')
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


            $count = $result->count();

            return DataTables::of($result)
                ->addIndexColumn()
                ->setRowId('id')
                ->editColumn('play_points', function ($row){
                    if ($row->transaction_type == 'win') {
                        $html = '<strong class="text-success">+ '.$row->winning_points.'</strong>';
                    } else {
                        $html = '<strong class="text-danger">- '.$row->play_points.'</strong>';
                    }
                    return $html;
                })
                ->editColumn('session', function ($row){
                    if ($row->session == 'open') {
                        $html = '<span class="badge badge-success">'.$row->session.'</span>';
                    } else {
                        $html = '<span class="badge badge-danger">'.$row->session.'</span>';
                    }
                    return $html;
                })
                ->addColumn('result', function ($row){
                    $winning_points = $row->winning_points;
                    if ($winning_points != 0) {
                        // Concatenate number and play_points for each individual record within the group
                        $individualRecords = DB::table('passbooks')
                            ->select(DB::raw('GROUP_CONCAT(CONCAT(number, " X ", play_points) SEPARATOR ", ") AS individualRecords'))
                            ->where('token', $row->token)
                            ->where('transaction_type', $row->transaction_type)
                            ->first();

                        $individualRecordsString = $individualRecords->individualRecords ?? '';

                        return $individualRecordsString;
                    } else {
                        return 'Transaction Type : Bid Play';
                    }
                })
                ->rawColumns(['session', 'play_points', 'result'])
                ->setTotalRecords($count)
                ->setFilteredRecords($count)
                ->toJson();
        }
        $html = $builder->columns([
            [
                'defaultContent' => '',
                'data'           => 'DT_RowIndex',
                'name'           => 'DT_RowIndex',
                'title'          => 'Id',
                'render'         => null,
                'orderable'      => false,
                'searchable'     => false,
                'exportable'     => false,
                'printable'      => true,
                'footer'         => '',
            ],
            ['data' => 'market_name', 'name' => 'market.name', 'title' => 'Market'],
            ['data' => 'user_name', 'name' => 'users.name', 'title' => 'User'],
            ['data' => 'play_points', 'title' => 'Points'],
            ['data' => 'total_points', 'title' => 'Balance'],
            ['data' => 'result', 'title' => 'Result'],
            ['data' => 'passbook_date', 'title' => 'Date'],
            ['data' => 'session', 'title' => 'Session'],
        ]);
        return view('admin.pages.users.passbook', compact('html'));
    }
    public function userBids(Builder $builder) {
        return $this->bids('user', $builder);
    }
    public function bidsManagement(Builder $builder) {
        return $this->bids('all', $builder);
    }
    public function bids($userBids, Builder $builder) {
        if (request()->ajax()) {
            $phone = request()->get('phone', null);
            $market = request()->get('market', null);
            $today = Carbon::now()->format('Y-m-d');
            $query = Bid::query()
                ->select(
                    'users.name as user_name',
                    'markets.name as market_name',
                    'bids.bid_type',
                    'bids.session',
                    'bids.number',
                    'bids.amount',
                    'bids.bid_date',
                    'bids.status',
                )
                ->join('users', 'bids.user_id', 'users.id')
                ->join('markets', 'bids.market_id', 'markets.id')
                ->orderBy('bids.id', 'DESC');
            if ($userBids == 'user') {
                $query->where('bids.user_id', request()->user_id)
                    ->whereDate('bids.bid_date', $today);
            }

            if (!empty($market)) {
                $query->where('markets.id', $market);
            }
            if (!empty($phone)) {
                $query->where('users.phone', $phone);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->setRowId('id')
                ->editColumn('bid_type', function ($row){
                    return str_replace('_', ' ', Str::title($row->bid_type));
                })
                ->editColumn('session', function ($row){
                    $session = $row->session;
                    if ($row->session == 'open') {
                        $html = '<span class="badge badge-success">'.$session.'</span>';
                    } else {
                        $html = '<span class="badge badge-danger">'.$session.'</span>';
                    }
                    return $html;
                })
                ->editColumn('status', function ($row){
                    $status = $row->status;
                    if ($status == 'pending') {
                        $html = '<span class="badge badge-danger">'.$status.'</span>';
                    } else {
                        $html = '<span class="badge badge-success">'.$status.'</span>';
                    }
                    return $html;
                })
                ->editColumn('bid_date', function ($row){
                    return Carbon::parse($row->bid_date)->format('d-m-Y h:i:s A');
                })
                ->rawColumns(['session', 'status'])
                ->make(true);
        }
        $html = $builder->columns([
            [
                'defaultContent' => '',
                'data'           => 'DT_RowIndex',
                'name'           => 'DT_RowIndex',
                'title'          => 'Id',
                'render'         => null,
                'orderable'      => false,
                'searchable'     => false,
                'exportable'     => false,
                'printable'      => true,
                'footer'         => '',
            ],
            ['data' => 'user_name', 'name' => 'users.name', 'title' => 'Name'],
            ['data' => 'market_name', 'name' => 'markets.name', 'title' => 'Game'],
            ['data' => 'bid_type', 'name' => 'bids.bid_type', 'title' => 'Type'],
            ['data' => 'session', 'name' => 'session', 'title' => 'Session'],
            ['data' => 'number', 'title' => 'Number'],
            ['data' => 'amount', 'title' => 'Amount'],
            ['data' => 'bid_date', 'name' => 'bids.bid_date', 'title' => 'Date'],
            ['data' => 'status', 'name' => 'status', 'title' => 'Status'],
        ]);
        $markets = Market::query()->select('id', 'name')->get();
        $phones = User::query()->select('phone')->orderBy('id', 'DESC')->get();
        if ($userBids == 'user') {
            $userId = request()->user_id;
            return view('admin.pages.users.bids', compact('html', 'markets', 'phones', 'userBids', 'userId'));
        } else {
            return view('admin.pages.users.bids', compact('html', 'markets', 'phones', 'userBids'));
        }
    }
    public function blockUser(Request $request) {

        try {
            $block_status = $request->status;
            $user_id = $request->id;
            $user = User::find($user_id);
            $user->block_status = $block_status;
            $user->save();
            if ($user) {
                if ($block_status) {
                    $msg = $user->name.' has been Blocked';
                } else {
                    $msg = $user->name.' has been Unblocked';
                }
                $output = ['success' => true,
                    'msg' => $msg
                ];
            } else {
                $output = ['success' => false,
                    'msg' => 'Something went wrong'
                ];
            }
        } catch (\Exception $e) {
            $output = ['success' => false,
                'msg' => $e->getMessage()
            ];
        }
        return $output;
    }
    public function referUsers(Request $request, Builder $builder) {
        if ($request->ajax()) {
            $query = User::query()
                ->select(
                    'users.name as user_name',
                    'users.email',
                )
                ->where('refer_from', $request->refer_code)
                ->orderBy('users.id', 'DESC');
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        $html = $builder->columns([
            [
                'defaultContent' => '',
                'data'           => 'DT_RowIndex',
                'name'           => 'DT_RowIndex',
                'title'          => 'Id',
                'render'         => null,
                'orderable'      => false,
                'searchable'     => false,
                'exportable'     => false,
                'printable'      => true,
                'footer'         => '',
            ],
            ['data' => 'user_name', 'name' => 'user_name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
        ]);
        return view('admin.pages.users.referred-users', compact('html'));
    }
    public function winnersList(Request $request, Builder $builder) {
        if ($request->ajax()) {
            $date = $request->date;
            $market = $request->market;
            $session = $request->market_session;
            $query = Passbook::query()
                ->select(
                    'markets.name as market_name',
                    'users.name as user_name',
                    'users.phone',
                    'passbooks.number',
                    'passbooks.play_points',
                )
                ->whereDate('passbooks.passbook_date', $date)
                ->where('passbooks.transaction_type', 'win')
                ->where('passbooks.session', $session)
                ->join('users', 'passbooks.user_id', 'users.id')
                ->join('markets', 'passbooks.market_id', 'markets.id')
                ->orderBy('passbooks.id', 'DESC');
            return DataTables::of($query)
                ->addIndexColumn()
                ->make(true);
        }
        $html = $builder->columns([
            [
                'defaultContent' => '',
                'data'           => 'DT_RowIndex',
                'name'           => 'DT_RowIndex',
                'title'          => 'Id',
                'render'         => null,
                'orderable'      => false,
                'searchable'     => false,
                'exportable'     => false,
                'printable'      => true,
                'footer'         => '',
            ],
            ['data' => 'market_name', 'name' => 'markets.name', 'title' => 'Market'],
            ['data' => 'user_name', 'name' => 'users.name', 'title' => 'User'],
            ['data' => 'number', 'name' => 'passbooks.number', 'title' => 'Number'],
            ['data' => 'play_points', 'name' => 'passbooks.play_points', 'title' => 'Amount'],
        ]);
        $markets = Market::query()->select('id', 'name')->get();
        return view('admin.pages.users.winners-list', compact('html', 'markets'));
    }
    public function destroy(Request $request) {
        try {
            $user = User::where('id', $request->id)->delete();
            if ($user) {
                $output = ['success' => true,
                    'msg' => 'User has been deleted'
                ];
            } else {
                $output = ['success' => false,
                    'msg' => 'Something went wrong'
                ];
            }
        } catch (\Exception $e) {
            $output = ['success' => false,
                'msg' => $e->getMessage()
            ];
        }
        return $output;
    }
    public function withdrawStatus(Request $request) {
        try {
            $status = $request->status;
            ($status == 1) ? $status = 0 : $status = 1;
            $withdraw = Setting::first();
            $withdraw->withdraw_status = $status;
            $withdraw->save();
            if ($withdraw) {
                $output = ['success' => true, 'msg' => 'Status Updated', 'status' => $status];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong!', 'status' => $status];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return $output;
    }
}
