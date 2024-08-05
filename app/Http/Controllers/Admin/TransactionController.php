<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Bid;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class TransactionController extends Controller
{
    public function transactions(Request $request, Builder $builder) {
        if (request()->ajax()) {
            $today = Carbon::now()->format('Y-m-d');
            $query = Transaction::query()
                ->from('transactions as t')
                ->select(
            't.transaction_type',
                    't.points',
                    'users.name',
                )
                ->where('t.user_id', $request->id)
                ->whereDate('t.transaction_date', $today)
                ->join('users', 't.user_id', 'users.id')
                ->orderBy('t.id', 'DESC');
            return DataTables::of($query)
                ->addIndexColumn()
                ->setRowId('id')
                ->addColumn('transaction_type', function ($row){
                    $status = $row->transaction_type;
                    if ($status == 'withdraw' || $status == 'pending_withdraw') {
                        $html = '<span class="badge badge-danger">'.$status.'</span>';
                    } else {
                        $html = '<span class="badge badge-success">'.$status.'</span>';
                    }
                    return $html;
                })
                ->rawColumns(['transaction_type'])
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
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'points', 'title' => 'Points'],
            ['data' => 'transaction_type', 'name' => 't.transaction_type', 'title' => 'Status'],
        ]);
        return view('admin.pages.users.transactions', compact('html'));
    }
}
