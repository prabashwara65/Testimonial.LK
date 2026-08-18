<?php
namespace Modules\Admin\Http\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Response;
use App\Models\Setting;

class DashboardService extends MainService
{
    public function responseVendorWiseCount($type)
    {
        $responses = Response::where('type', $type)
            ->where('status', 'approved')
            ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->get();

        $data['questionnaireCount'] = $this->groupByMonth($responses);


        $recordTypes = ['video', 'audio', 'image', 'text'];
        foreach($recordTypes as $recordType)
        {
            $responses = Response::where('vendor_company_id', $vendor_company_id)
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

    public function responseCount($vendor_company_id, $type)
    {
        $responses = Response::where('vendor_company_id', $vendor_company_id)
        ->where('response_type', 'Questionnaire')
        ->where('type', $type)
        ->where('status', 'approved')
        ->whereBetween('created_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
        ->get();

        $data['questionnaireCount'] = $this->groupByMonth($responses);


        $recordTypes = ['video', 'audio', 'image', 'text'];
        foreach($recordTypes as $recordType)
        {
            $responses = Response::where('vendor_company_id', $vendor_company_id)
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

    public function responseRating($vendor_company_id, $type)
    {
        $responses = Response::select('rating', DB::raw('count(*) as count'))->where('vendor_company_id', $vendor_company_id)
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
}
