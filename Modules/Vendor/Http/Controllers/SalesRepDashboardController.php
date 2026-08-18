<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

use Illuminate\Http\Request;

use App\Models\Response;
use App\Models\Campaign;

use Modules\Vendor\Http\Services\SalesRepDashboardService;

class SalesRepDashboardController extends Controller
{
    public function __construct(SalesRepDashboardService $salesRepDashboardService)
    {
        $this->salesRepDashboardService = $salesRepDashboardService;
    }

    public function index(Request $request) {

        $user_id = auth()->user()->id;

        $data['title'] = 'Dashboard';

        $data['campaigns'] = Campaign::where('status', 1)
            ->whereHas('employees', function ($q) use ($user_id){
                $q->where('vendor_id', $user_id);
            })->get();

        if(count($data['campaigns']) > 0) {

            if($request->has('start_date') && $request->has('end_date'))
            {
                $start_date = $request->start_date;
                $end_date = $request->end_date;
                session()->put('start_date', $start_date);
                session()->put('end_date', $end_date);
            } elseif (session()->get('campaign_id')) {
                $start_date = session()->get('start_date');
                $end_date = session()->get('end_date');
            } else {
                $end_date = strtotime(now());
                $start_date = strtotime('-7 day', $end_date);

                $start_date = date('Y-m-d', $start_date);
                $end_date = date('Y-m-d', $end_date);
            }

            if($request->has('campaign'))
            {
                $campaign_id = $request->campaign;
                session()->put('campaign_id', $campaign_id);
            } elseif (session()->get('campaign_id')) {
                $campaign_id = session()->get('campaign_id');
            } else {
                $campaign_id = $data['campaigns']->first()->id;
            }

            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $data['campaign_id'] = $campaign_id;

            $data['earning'] = $this->salesRepDashboardService->totalEarning($user_id, $start_date, $end_date);

            $data['target'] = $this->salesRepDashboardService->target($campaign_id, $user_id);

            $data['testimonial_total_count'] = $this->salesRepDashboardService->totalResponseCount($campaign_id, $user_id, 1);
            $data['feedback_total_count'] = $this->salesRepDashboardService->totalResponseCount($campaign_id, $user_id, 2);

            $data['testimonial_type_count'] = $this->salesRepDashboardService->responseCount($campaign_id, $user_id, 1);
            $data['feedback_type_count'] = $this->salesRepDashboardService->responseCount($campaign_id, $user_id, 2);

            $data['testimonial_rating_count'] = $this->salesRepDashboardService->responseRating($campaign_id, $user_id, 1);
            $data['feedback_rating_count'] = $this->salesRepDashboardService->responseRating($campaign_id, $user_id, 2);
            
        }


        return view('vendor::salesrep.dashboard', $data);
    }
}
