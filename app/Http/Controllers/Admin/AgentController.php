<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class AgentController extends Controller
{
    public function index(Builder $builder) {
        if (request()->ajax()) {
            $query = User::query()
                ->select(
        'users.id',
                'users.user_type',
                'users.name',
                'users.email',
                'users.phone',
                'users.points',
                'users.refercode',
            )
                ->where('user_type', 'agent')
                ->orderBy('users.id', 'DESC');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('users', function ($row){
                    $html =  '<a href="'.action('Admin\UserController@referUsers', ['refer_code' => $row->refercode]).'" class="btn btn-sm btn-primary">Users</a>';
                    return $html;
                })
                ->addColumn('agents', function ($row){
                    $status = $row->user_type;
                    $html =  '<a href="'.action('Admin\AgentController@markAgent', ['id' => $row->id, 'type' => $status]).'" class="btn btn-sm btn-danger mark-agent">Remove from Agent</a>';
                    return $html;
                })
                ->addColumn('action', function ($row){
                    $html =  '<a href="'.action('Admin\UserController@depositWithdrawPoint', ['id' => $row->id, 'type' => 'deposit']).'" class="btn btn-sm btn-success mb-2 deposit-withdraw-point"><i class="fas fa-plus"></i></a>
                             <a href="'.action('Admin\UserController@depositWithdrawPoint', ['id' => $row->id, 'type' => 'withdraw']).'" class="btn btn-sm btn-danger mb-2 deposit-withdraw-point"><i class="fas fa-minus"></i></a>
                             <a href="'.action('Admin\TransactionController@transactions', ['id' => $row->id]).'" class="btn btn-sm btn-primary mb-2"><i class="fas fa-bars"></i></a>
                             <a href="'.action('Admin\UserController@destroy', ['id' => $row->id]).'" class="btn btn-sm btn-danger mb-2 delete-user"><i class="fas fa-times"></i></a>';
                    return $html;
                })
                ->rawColumns(['users', 'agents', 'action'])
                ->make(true);
        }
        $html = $builder->columns([
            [
                'defaultContent' => '',
                'data'           => 'DT_RowIndex',
                'name'           => 'users.id',
                'title'          => 'Id',
                'render'         => null,
                'orderable'      => false,
                'searchable'     => false,
                'exportable'     => false,
                'printable'      => true,
                'footer'         => '',
            ],
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'email', 'name' => 'email', 'title' => 'Email'],
            ['data' => 'phone', 'name' => 'phone', 'title' => 'Phone'],
            ['data' => 'users', 'title' => 'Users'],
            ['data' => 'points', 'title' => 'Points'],
            ['data' => 'refercode', 'name' => 'refercode', 'title' => 'Refer Code'],
            ['data' => 'agents', 'title' => 'Agents'],
            ['data' => 'action', 'title' => 'Manage'],
        ]);
        return view('admin.pages.agents.index', compact('html'));
    }
    public function markAgent(Request $request) {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $user_type = $request->type;
                if ($user_type == 'agent') {
                    $user_type = 'user';
                } else {
                    $user_type = 'agent';
                }
                $six_digit_random_number = mt_rand(100000, 999999);
                $refercode = "RKO-" . $six_digit_random_number;
                $user = User::find($id);
                $user->user_type = $user_type;
                $user->refercode = $refercode;
                $user->save();
                if ($user) {
                    if ($user_type == 'user') {
                        $msg = $user->name.' removed from agent';
                    } else {
                        $msg = $user->name.' marked as agent';
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
    }
}
