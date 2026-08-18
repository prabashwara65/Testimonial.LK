<?php
namespace Modules\Vendor\Http\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use DataTables;

use App\Models\Response;
use App\Models\Setting;

class DashboardService extends MainService
{
    public function responseCount($type)
    {
        $responses = Response::where('vendor_company_id', auth()->user()->vendor_company_id)
        ->where('response_type', 'Questionnaire')
        ->where('type', $type)
        ->where('status', 'approved')
        ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
        ->get();

        $data['questionnaireCount'] = $this->groupByMonth($responses);


        $recordTypes = ['video', 'audio', 'image', 'text'];
        foreach($recordTypes as $recordType)
        {
            $responses = Response::where('vendor_company_id', auth()->user()->vendor_company_id)
            ->where('response_type', 'Record')
            ->where('type', $type)
            ->where('status', 'approved')
            ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->whereHas('responseRecord', function ($q) use ($recordType) {
                $q->whereNotNull($recordType);
            })->get();

            $data[$recordType.'Count'] = $this->groupByMonth($responses);
        }

        return $data;
    }

    public function groupByMonth($responses)
    {
        $responses = $responses->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('m'); // grouping by months
        });

        $count = [];
        $arr = [];

        foreach ($responses as $key => $value) {
            $count[(int)$key] = $value->count();
        }

        for($i = 1; $i <= 12; $i++){
            if(!empty($count[$i])){
                $arr[$i] = $count[$i];
            }else{
                $arr[$i] = 0;
            }
        }

        return $arr;
    }

    public function responseRating($type)
    {
        $responses = Response::select('rating', DB::raw('count(*) as count'))->where('vendor_company_id', auth()->user()->vendor_company_id)
        ->where('type', $type)
        ->where('status', 'approved')
        ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
        ->groupBy('rating')
        ->get();

        $count = [];
        $arr = [];

        foreach ($responses as $value) {
            $count[$value->rating] = $value->count;
        }

        $ratingScore = Setting::select('value')->find(2)->value;
        for($i = $ratingScore; $i > 0; $i--){
            if(!empty($count[$i])){
                $arr[$i] = $count[$i];
            }else{
                $arr[$i] = 0;
            }
        }

        $data['ratingScore'] = $ratingScore;
        $data['ratingCount'] = $arr;

        return $data;
    }

    public function getDataTable($data)
    {
        return DataTables::of($data)
            ->editColumn('created_at', '{{ date("Y/m/d - h:i A", strtotime($created_at)) }}')
            ->addColumn('employee_name', function ($response) {
                return $response->employee->name . ' ' . $response->employee->last_name;
            })
            ->addColumn('customer_name', function ($response) {
                return $response->user->name . ' ' . $response->user->last_name;
            })
            ->editColumn('type', '{{ ($type == "1") ? "Testimonial" : "Feedback" }}')
            ->addColumn('details', function ($response) {
                return $this->generateActionButtons('vendor.dashboard', $response->id, ['view' => true, 'edit' => false, 'delete' => false]);
            })
            ->rawColumns(['details'])
            ->make(true);
    }
}
