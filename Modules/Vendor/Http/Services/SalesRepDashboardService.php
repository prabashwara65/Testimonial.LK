<?php
namespace Modules\Vendor\Http\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Campaign;
use App\Models\Response;
use App\Models\Setting;

class SalesRepDashboardService extends MainService
{
    public function target($campaign_id, $user_id)
    {
        $campaign = Campaign::find($campaign_id);

        if($campaign->target->target_type == 1){
            $data['target_achieved']['common'] = Response::where('campaign_id', $campaign_id)
            ->where('emp_id', $user_id)
            ->whereBetween('created_at', [$campaign->start_date, $campaign->end_date])
            ->count();

            $data['target_remaining']['common'] = $campaign->target->target - $data['target_achieved']['common'];
        }
        else {
            // Special Target
            $recordTypes = ['video', 'audio', 'image', 'text'];
            foreach($recordTypes as $recordType)
            {
                $record = Response::where('campaign_id', $campaign_id)
                ->where('emp_id', $user_id)
                ->where('response_type', 'Record')
                ->whereBetween('created_at', [$campaign->start_date, $campaign->end_date])
                ->whereHas('responseRecord', function ($q) use ($recordType) {
                    $q->whereNotNull($recordType);
                })->count();

                if($campaign->target->$recordType) {
                    $data['target_achieved'][$recordType] = $record;
                    if($campaign->target->$recordType > $record) {
                        $data['target_remaining'][$recordType] = $campaign->target->$recordType - $record;
                    } else {
                        $data['target_remaining'][$recordType] = 0;
                    }
                }
            }
        }

        return $data;
    }

    public function totalResponseCount($campaign_id, $user_id, $type)
    {
        $response = Response::where('campaign_id', $campaign_id)
            ->where('emp_id', $user_id)
            ->where('type', $type)
            ->get();

        $data['total'] = $response->count();
        $data['approved'] = $response->where('status', 'approved')->count();
        $data['reject'] = $response->where('status', 'reject')->count();
        $data['pending'] = $response->where('status', 'pending')->count();

        return $data;
    }

    public function responseCount($campaign_id, $user_id, $type)
    {
        $recordTypes = ['video', 'audio', 'image', 'text'];
        foreach($recordTypes as $recordType)
        {
            $responses = Response::where('campaign_id', $campaign_id)
                ->where('emp_id', $user_id)
                ->where('response_type', 'Record')
                ->where('type', $type)
                ->where('status', 'approved')
                ->whereHas('responseRecord', function ($q) use ($recordType) {
                    $q->whereNotNull($recordType);
                })->count();

            $data[$recordType.'Count'] = $responses;
        }

        $responses = Response::where('campaign_id', $campaign_id)
            ->where('emp_id', $user_id)
            ->where('response_type', 'Questionnaire')
            ->where('type', $type)
            ->where('status', 'approved')
            ->count();

        $data['questionnaireCount'] = $responses;

        return $data;
    }

    public function responseRating($campaign_id, $user_id, $type)
    {
        $responses = Response::select('rating', DB::raw('count(*) as count'))
            ->where('campaign_id', $campaign_id)
            ->where('emp_id', $user_id)
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

    Public function totalEarning($user_id, $start_date, $end_date)
    {
        $earning = 0;

        $campaigns = Campaign::whereHas('employees', function ($q) use ($user_id) {
                                    $q->where('vendors.id', $user_id);
                                })->get();

        foreach($campaigns as $campaign) {
            $totalRecord = 0;

            $recordTypes = ['video', 'audio', 'image', 'text'];
            foreach($recordTypes as $recordType)
            {
                $record = Response::where('emp_id', $user_id)
                    ->where('campaign_id', $campaign->id)
                    ->where('response_type', 'Record')
                    ->whereBetween('created_at', [$start_date, $end_date])
                    ->whereHas('responseRecord', function ($q) use ($recordType) {
                        $q->whereNotNull($recordType);
                    })->count();

                $totalRecord += $record;
            }

            $questionnaire = Response::where('emp_id', $user_id)
                ->where('response_type', 'Questionnaire')
                ->whereBetween('created_at', [$start_date, $end_date])->count();

            $totalRecord += $questionnaire;

            if($campaign->incentive_rate)
            {
                $earning = $totalRecord * $campaign->incentive_rate;
            }

            $earning += $earning;
        }

        return number_format($earning);
    }
}
