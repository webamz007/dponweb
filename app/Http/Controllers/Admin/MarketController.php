<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Bid;
use App\Models\Market;
use App\Models\MarketDetail;
use App\Models\Passbook;
use App\Models\Result;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class MarketController extends Controller
{
    public function index(Builder $builder) {
       return $this->indexMarkets('other' ,$builder);
    }
    public function starlineMarkets(Builder $builder) {
        return $this->indexMarkets('starline' ,$builder);
    }
    public function delhiMarkets(Builder $builder) {
        return $this->indexMarkets('delhi' ,$builder);
    }
    public function indexMarkets($type, $builder) {
        if (request()->ajax()) {
            $query = Market::query()
                ->select('id', 'name', 'market_type')
                ->where('market_type', $type)
                ->orderBy('position');
            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('market_type', function ($row){
                    $type = $row->market_type;
                    $html = '<span class="badge badge-success">'.ucfirst($type).'</span>';
                    return $html;
                })
                ->addColumn('action', function ($row){
                    $html = '<a href="'.action('Admin\MarketController@edit', ['id' => $row->id]).'" class="btn btn-primary edit-market">Edit</a>
                             <a href="'.action('Admin\MarketController@destroy', ['id' => $row->id]).'" class="btn btn-danger delete-market">Delete</a>';
                    return $html;
                })
                ->setRowAttr([
                    'data-id' => function ($row) {
                        return $row->id;
                    }])
                ->setRowClass(function ($row) {
                    return 'sort-row';
                })
                ->rawColumns(['market_type', 'action'])
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
            ['data' => 'market_type', 'name' => 'status', 'title' => 'Market Type'],
            ['data' => 'action', 'searchable' => false, 'title' => 'Action']
        ]);
        return view('admin.pages.markets.index', compact('html', 'type'));
    }
    public function edit(Request $request) {
        $id = $request->id;
        $market = Market::find($id);
        return view('admin.pages.markets.edit-modal', compact('market'));
    }
    public function createNewTypeMarket(Request $request) {
        if ($request->ajax()) {
            try {
                $market = new Market();
                $market->name = $request->market_name;
                $market->market_type = $request->market_type;
                $market->save();
                if ($market) {
                    $output = ['success' => true, 'msg' => 'Market Added!'];
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
        return redirect()->back();
    }
    public function update(Request $request) {
        if ($request->ajax()) {
            try {
                $id = $request->market_id;
                $market_name = $request->market_name;
                $market = Market::find($id);
                $market->name = $market_name;
                $market->save();
                if ($market) {
                    $output = ['success' => true, 'msg' => 'Market updated!'];
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
        return redirect()->back();
    }
    public function store(Request $request) {
        if ($request->ajax()) {
            try {
                $request->validate([
                    'market_name' => 'required',
                ]);
                $market_name = $request->market_name;
                $market_type = $request->market_type ?? 'other';
                $open_time = Carbon::parse($request->open_time)->format('Y-m-d H:i:s');
                $close_time = Carbon::parse($request->close_time)->format('Y-m-d H:i:s');
                $open_result = Carbon::parse($request->open_result)->format('Y-m-d H:i:s');
                $close_result = Carbon::parse($request->close_result)->format('Y-m-d H:i:s');
                $market = new Market();
                $market->name = $market_name;
                $market->market_type = $market_type;
                $market->save();
                if ($market) {
                    $weekStart = Carbon::now()->startOfWeek(); // get the start of the week
                    $weekEnd = Carbon::now()->endOfWeek(); // get the end of the week

                    $period = CarbonPeriod::create($weekStart, $weekEnd); // create a period of time for the week

                    foreach ($period as $date) {
                        $dayName = $date->format('l'); // get the day name
                        $detail = new MarketDetail();
                        $detail->market_id = $market->id;
                        $detail->oet = $open_time;
                        $detail->cet = $close_time;
                        $detail->ort = $open_result;
                        $detail->crt = $close_result;
                        $detail->status = 'true';
                        $detail->day = lcfirst($dayName);
                        $detail->save();
                    }
                    $output = ['success' => true, 'msg' => 'Market added!'];
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
        return redirect()->back();
    }
    public function destroy(Request $request) {
        if ($request->ajax()) {
            try {
                $id = $request->id;
                $market = Market::where('id', $id)->delete();
                MarketDetail::where('market_id', $id)->delete();
                Bid::where('market_id', $id)->delete();
                Passbook::where('market_id', $id)->delete();
                Result::where('market_id', $id)->delete();
                if ($market) {
                    $output = ['success' => true, 'msg' => 'Market deleted!'];
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
        return redirect()->back();
    }
    public function marketsStatus(Request $request) {
        try {
            $status = $request->status;
            if ($status == 'true') {
                $status = 'false';
            } else {
                $status = 'true';
            }
            $market = MarketDetail::query()->update(['status' => $status]);
            if ($market) {
                if ($status == 'false') {
                    $msg = 'All Markets Has Been Closed';
                } else {
                    $msg = 'All Markets Has Been Opened';
                }
                $output = ['success' => true, 'msg' => $msg];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong!'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return redirect()->route('game.settings')->with('status', $output);
    }
    public function gameSettings(Builder $builder) {
        if (request()->ajax()) {
            $query = Market::query()
                ->select('markets.name', 'markets.id')
                ->join('market_details', 'markets.id', 'market_details.market_id')
                ->where('market_type', 'other')
                ->groupBy('markets.id')
                ->orderBy('markets.id', 'DESC')
                ->get();
            return DataTables::of($query)
                ->editColumn('monday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'monday');
                })
                ->editColumn('tuesday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'tuesday');
                })
                ->editColumn('wednesday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'wednesday');
                })
                ->editColumn('thursday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'thursday');
                })
                ->editColumn('friday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'friday');
                })
                ->editColumn('saturday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'saturday');
                })
                ->editColumn('sunday', function ($row){
                    return $this->gameSettingMarketDetails($row->id, 'sunday');
                })
                ->rawColumns(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                ->make(true);
        }
        $html = $builder->columns([
            ['data' => 'name', 'name' => 'name', 'title' => 'Name'],
            ['data' => 'monday', 'searchable' => false, 'title' => 'Monday'],
            ['data' => 'tuesday', 'searchable' => false, 'title' => 'Tuesday'],
            ['data' => 'wednesday', 'searchable' => false, 'title' => 'Wednesday'],
            ['data' => 'thursday', 'searchable' => false, 'title' => 'Thursday'],
            ['data' => 'friday', 'searchable' => false, 'title' => 'Friday'],
            ['data' => 'saturday', 'searchable' => false, 'title' => 'Saturday'],
            ['data' => 'sunday', 'searchable' => false, 'title' => 'Sunday'],
        ]);
        return view('admin.pages.markets.game-settings', compact('html'));
    }
    public function gameSettingMarketDetails($id, $day, $market_type = 'other' ) {
        $detail = MarketDetail::where('market_id', $id)->where('day', $day)->first();
        if ($market_type == 'delhi') {
            $end_time = 'BET';
            $result_time = 'BRT';
        } else {
            $end_time = 'OET';
            $result_time = 'ORT';
        }
        $html = '';
        $html .= "<span class='d-block mb-3'>Active : " . $detail->status . "</span>";
        $html .= "<span class='d-block mb-3'>".$end_time." : " . Carbon::parse($detail->oet)->format('h:i A') ."</span>";
        if ($market_type == 'other') {
            $html .= "<span class='d-block mb-3'>CET : "  . Carbon::parse($detail->cet)->format('h:i A') . "</span>";
        }
        $html .= "<span class='d-block mb-3'>".$result_time." : " . Carbon::parse($detail->ort)->format('h:i A') . "</span>";
        if ($market_type == 'other') {
            $html .= "<span class='d-block mb-3'>CRT : " . Carbon::parse($detail->crt)->format('h:i A') . "</span>";
        }
        $html .= "<a class='btn btn-primary btn-sm edit-game-settings' href='".action('Admin\MarketController@gameSettingsEdit', ['id' => $id, 'day' => $day])."'>Update</a>";
        return $html;
    }
    public function gameSettingsEdit(Request $request) {
        $id = $request->id;
        $day = $request->day;
        $market = MarketDetail::where('market_id', $id)->where('day', $day)->first();
        $type = Market::where('id', $id)->value('market_type');
        if ($type == 'starline' || $type == 'delhi') {
            return view('admin.pages.markets.edit-starline-settings', compact('market', 'type'));
        } else {
            return view('admin.pages.markets.edit-game-settings', compact('market', 'type'));
        }
    }
    public function gameSettingsUpdate(Request $request) {
        try {
            $week = $request->week;
            $data = [
                'status' => $request->status,
                'oet' => Carbon::parse($request->open_time)->format('Y-m-d H:i:s'),
                'cet' => Carbon::parse($request->close_time)->format('Y-m-d H:i:s'),
                'ort' => Carbon::parse($request->open_result)->format('Y-m-d H:i:s'),
                'crt' => Carbon::parse($request->close_result)->format('Y-m-d H:i:s'),
            ];
            if ($week == 'true') {
                $settings =  MarketDetail::where('market_id', $request->id)->update($data);
            } else {
                $settings =  MarketDetail::where('market_id', $request->id)->where('day', $request->day)->update($data);
            }
            if ($settings) {
                $output = ['success' => true, 'msg' => 'Market Updated'];
            } else {
                $output = ['success' => false, 'msg' => 'Something Went Wrong!'];
            }
        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
        }
        return $output;
    }
    public function sortMarket(Request $request)
    {
        try {
            $data = $request->input('order');
            foreach ($data as $value) {
                $market = Market::find($value['id']);
                $market->position = $value['position'];
                $market->save();
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
        return response()->json(['success' => true, 'msg' => 'Markets Sorted']);
    }
    public function starlineSettings() {
        return $this->marketSettings('starline');
    }
    public function delhiSettings() {
        return $this->marketSettings('delhi');
    }
    public function marketSettings($market_type) {
        if (request()->ajax()) {
            $query = Market::query()
                ->select('markets.name', 'markets.id')
                ->join('market_details', 'markets.id', 'market_details.market_id')
                ->where('market_type', $market_type)
                ->groupBy('markets.id')
                ->orderBy('markets.id', 'DESC')
                ->get();
            return DataTables::of($query)
                ->editColumn('monday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'monday', $market_type);
                })
                ->editColumn('tuesday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'tuesday', $market_type);
                })
                ->editColumn('wednesday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'wednesday', $market_type);
                })
                ->editColumn('thursday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'thursday', $market_type);
                })
                ->editColumn('friday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'friday', $market_type);
                })
                ->editColumn('saturday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'saturday', $market_type);
                })
                ->editColumn('sunday', function ($row) use($market_type){
                    return $this->gameSettingMarketDetails($row->id, 'sunday', $market_type);
                })
                ->rawColumns(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                ->make(true);
        }
        $type = $market_type;
        if ($type == 'starline') {
            return view('admin.pages.markets.starline-settings',compact('type'));
        } else {
            return view('admin.pages.markets.delhi-settings',compact('type'));
        }
    }
}
