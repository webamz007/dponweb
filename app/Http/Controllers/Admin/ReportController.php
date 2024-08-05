<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Exports\ExportWithdrawReport;
use App\Models\Bid;
use App\Models\Market;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Facades\DataTables;

class ReportController extends Controller
{
    public function generalReport() {
        if (request()->ajax()) {
            $date = request()->get('date', null);
            $market = request()->get('market', null);
            $session = request()->get('session', null);
            $type = request()->get('type', null);
            $query = Bid::query()->selectRaw('SUM(amount) as amount, bid_type')->groupBy('bid_type');
            if (!empty($date)) {
                $date = Carbon::parse($date)->format('Y-m-d');
                $query->whereDate('bid_date', $date);
            }
            if (!empty($market)) {
                $query->where('market_id', $market);
            }
            if (!empty($session)) {
                $query->where('session', $session);
            }
            if (!empty($type)) {
                $query->where('bid_type', $type);
            }
            return DataTables::of($query->get())
                ->editColumn('bid_type', function ($row) use ($type){
                    return str_replace('_', ' ', Str::title($row->bid_type));
                })
                ->addColumn('equal', '=')
                ->make(true);
        }
        $markets = Market::query()->select('id', 'name')->get();
        $types = Bid::query()->select('bid_type')->groupBy('bid_type')->get();
        return view('admin.pages.reports.reports', compact('markets', 'types'));
    }
    public function typeReport() {
        if (request()->ajax()) {
            $date = request()->get('date', null);
            $market = request()->get('market', null);
            $session = request()->get('session', null);
            $type = request()->get('type', null);
            $query = Bid::query()->select('amount', 'number');
            if (!empty($date)) {
                $date = Carbon::parse($date)->format('Y-m-d');
                $query->whereDate('bid_date', $date);
            }
            if (!empty($market)) {
                $query->where('market_id', $market);
            }
            if (!empty($type)) {
                $query->where('bid_type', $type);
            }
            if (!empty($session)) {
                $query->where('session', $session);
            }
            return DataTables::of($query->get())
                ->editColumn('number', function ($row) use ($type){
                    return $row->number;
                })
                ->addColumn('equal', '=')
                ->make(true);
        }
        return view('admin.pages.reports.reports');
    }
    public function withdrawReport(Builder $builder) {
       return $this->report('withdraw', $builder);
    }
    public function depositReport(Builder $builder) {
        return $this->report('deposit', $builder);
    }
    public function report($reportType, $builder) {
        if (request()->ajax()) {
            $query = Transaction::query()
                ->from('transactions as t')
                ->select(
                    't.id',
                    'users.name',
                    'users.upi',
                    'users.ifsc',
                    'users.bank',
                    'users.phone',
                    't.transaction_date',
                    't.points',
                    't.status',
                    't.transaction_type',
                    't.transaction_creator',
                )
                ->join('users', 't.user_id', 'users.id')
                ->orderBy('t.id', 'DESC');
            if ($reportType == 'deposit') {
                $query->where('t.transaction_type', 'deposit');
            } else {
                $query->where('t.transaction_type', 'withdraw')
                    ->where(function ($query) {
                        $query->where(function ($q) {
                            $q->where('t.status', 'pending')
                                ->where('t.transaction_creator', 'user');
                        })
                        ->orWhere(function ($q) {
                            $q->where('t.status', 'complete')
                                ->where('t.transaction_creator', 'user');
                        })
                        ->orWhere(function ($q) {
                            $q->where('t.status', 'complete')
                                ->where('t.transaction_creator', 'admin');
                        });
                    });
            }
            return DataTables::of($query)
                ->editColumn('status', function ($row){
                    $status = $row->status;
                    if ($status == 'complete') {
                        $html = '<span class="badge badge-success">'.ucfirst($status).'</span>';
                    } else {
                        $html = '<span class="badge badge-danger">'.ucfirst($status).'</span>';
                    }
                    return $html;
                })
                ->editColumn('transaction_creator', function ($row){
                    $transaction_creator = $row->transaction_creator;
                    $html = '<span class="badge badge-success">'.ucfirst($transaction_creator).'</span>';
                    return $html;
                })
                ->editColumn('transaction_date', function ($row){
                    return Carbon::parse($row->date)->format('d-m-Y h:i:s A');
                })
                ->addColumn('withdraw', function ($row){
                    $html = '<a href="'.action('Admin\ReportController@completeWithdraw', ['id' => $row->id]).'" class="btn btn-sm btn-primary withdraw">Complete Withdraw</a>';
                    return $html;
                })
                ->rawColumns(['withdraw', 'status', 'transaction_creator'])
                ->make(true);
        }
        if ($reportType == 'withdraw') {
            $html = $builder->columns([
                ['data' => 'id', 'name' => 't.id', 'title' => 'ID'],
                ['data' => 'name', 'name' => 'users.name', 'title' => 'User'],
                ['data' => 'upi', 'name' => 'users.upi', 'title' => 'UPI'],
                ['data' => 'ifsc', 'name' => 'users.ifsc', 'title' => 'IFSC'],
                ['data' => 'bank', 'name' => 'users.bank', 'title' => 'Bank'],
                ['data' => 'phone', 'name' => 'users.phone', 'title' => 'Phone'],
                ['data' => 'transaction_date', 'searchable' => false, 'title' => 'Original Date'],
                ['data' => 'points', 'name' => 't.points', 'title' => 'Points'],
                ['data' => 'status', 'name' => 't.status', 'title' => 'Status'],
                ['data' => 'transaction_creator', 'name' => 't.transaction_creator', 'title' => 'Transaction Creator'],
                ['data' => 'withdraw', 'searchable' => false, 'title' => 'Complete Withdraw'],
            ]);
        } else {
            $html = $builder->columns([
                ['data' => 'id', 'name' => 't.id', 'title' => 'ID'],
                ['data' => 'name', 'name' => 'users.name', 'title' => 'User'],
                ['data' => 'phone', 'name' => 'users.phone', 'title' => 'Phone'],
                ['data' => 'transaction_date', 'searchable' => false, 'title' => 'Original Date'],
                ['data' => 'points', 'searchable' => false, 'title' => 'Points'],
                ['data' => 'status', 'name' => 't.status', 'title' => 'Status'],
                ['data' => 'transaction_creator', 'searchable' => false, 'title' => 'Deposit By'],
            ]);
        }
        return view('admin.pages.reports.deposit-withdraw-report', compact('html', 'reportType'));
    }
    public function completeWithdraw(Request $request) {
        try {
            $id = $request->id;
            $transaction = Transaction::find($id);
            $transaction->status = 'complete';
            $transaction->save();
            if ($transaction) {
                $output = ['success' => true, 'msg' => 'Withdraw has been completed'];
            } else {
                $output = ['success' => false, 'msg' => 'Something went wrong!'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }

        return redirect()->route('report.withdraw')->with('status', $output);
    }
    public function exportWithdrawReport()
    {
        $date = request()->date;
        $report_type = request()->report_type;
        return Excel::download(new ExportWithdrawReport($report_type, $date), 'withdraw_report.xlsx');
    }
}
