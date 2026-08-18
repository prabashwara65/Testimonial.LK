<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\ApiController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Models\Response;

use Modules\Vendor\Http\Services\SalesRepDashboardService;


class DashboardController extends ApiController
{
    public function __construct(SalesRepDashboardService $salesRepDashboardService)
    {
        $this->salesRepDashboardService = $salesRepDashboardService;
    }

    public function dashboard(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_company_id' => 'required',
                'emp_id' => 'required',
                'campaign_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            // Total Count Pie Charts
            $testimonial_total_count = $this->salesRepDashboardService->totalResponseCount($request->campaign_id, $request->emp_id, 1);
            $feedback_total_count = $this->salesRepDashboardService->totalResponseCount($request->campaign_id, $request->emp_id, 2);

            $tTotal = $testimonial_total_count['total'];
            $tApproved = ($testimonial_total_count['approved'] == 0) ? 0 : (($testimonial_total_count['approved'] / $tTotal) * 100);
            $tReject = ($testimonial_total_count['reject'] == 0) ? 0 : (($testimonial_total_count['reject'] / $tTotal) * 100);
            $tPending = ($testimonial_total_count['pending'] == 0) ? 0 : (($testimonial_total_count['pending'] / $tTotal) * 100);

            $fTotal = $feedback_total_count['total'];
            $fApproved = ($feedback_total_count['approved'] == 0) ? 0 : (($feedback_total_count['approved'] / $fTotal) * 100);
            $fReject = ($feedback_total_count['reject'] == 0) ? 0 : (($feedback_total_count['reject'] / $fTotal) * 100);
            $fPending = ($feedback_total_count['pending'] == 0) ? 0 : (($feedback_total_count['pending'] / $fTotal) * 100);

            // Target Bar Chart
            $target = $this->salesRepDashboardService->target($request->campaign_id, $request->emp_id);

            foreach($target['target_achieved'] as $key => $v)
            {
                $target_legend[] = ucfirst($key);
                $target_data[] = [$target['target_achieved'][$key], $target['target_remaining'][$key]];
            }

            // Response Count Bar Chart
            $testimonial_type_counts = $this->salesRepDashboardService->responseCount($request->campaign_id, $request->emp_id, 1);
            $testimonial_type_count = array_values($testimonial_type_counts);

            $feedback_type_counts = $this->salesRepDashboardService->responseCount($request->campaign_id, $request->emp_id, 2);
            $feedback_type_count = array_values($feedback_type_counts);

            // Response Rating Bar Chart
            $testimonial_rating_counts = $this->salesRepDashboardService->responseRating($request->campaign_id, $request->emp_id, 1);
            $testimonial_rating_count = array_values($testimonial_rating_counts['ratingCount']);

            $feedback_rating_counts = $this->salesRepDashboardService->responseRating($request->campaign_id, $request->emp_id, 2);
            $feedback_rating_count = array_values($feedback_rating_counts['ratingCount']);

            for($i=$testimonial_rating_counts['ratingScore']; $i>0; $i--) {
                $rating_count_legend[] = $i .' Star';
            }

            // Chart JSON Format
            $data = [
                    'chart' => [
                                    [
                                        'name' => 'Testimonial Total Count '.$tTotal,
                                        'values' => [
                                                        [ 'percentage' => $tApproved, 'color' => '#033076' ],
                                                        [ 'percentage' => $tReject, 'color' => '#E7231E' ],
                                                        [ 'percentage' => $tPending, 'color' => '#FFC300' ]
                                                    ],
                                        'colors' => ['#033076', '#E7231E', '#FFC300'],
                                        'type' => 'pie_chart',
                                        'attributes' => [
                                                            [ 'color' => '#033076', 'name' => 'Approved', 'value' => $testimonial_total_count['approved'] ],
                                                            [ 'color' => '#E7231E', 'name' => 'Reject', 'value' => $testimonial_total_count['reject'] ],
                                                            [ 'color' => '#FFC300', 'name' => 'Pending', 'value' => $testimonial_total_count['pending'] ]
                                                        ]
                                    ],
                                    [
                                        'name' => 'Feedback Total Count '.$fTotal,
                                        'values' => [
                                                        [ 'percentage' => $fApproved, 'color' => '#033076' ],
                                                        [ 'percentage' => $fReject, 'color' => '#E7231E' ],
                                                        [ 'percentage' => $fPending, 'color' => '#FFC300' ]
                                                    ],
                                        'colors' => ['#033076', '#E7231E', '#FFC300'],
                                        'type' => 'pie_chart',
                                        'attributes' => [
                                                            [ 'color' => '#033076', 'name' => 'Approved', 'value' => $feedback_total_count['approved'] ],
                                                            [ 'color' => '#E7231E', 'name' => 'Reject', 'value' => $feedback_total_count['reject'] ],
                                                            [ 'color' => '#FFC300', 'name' => 'Pending', 'value' => $feedback_total_count['pending'] ]
                                                        ]
                                        ],
                                        [
                                            'name' => 'Target Details',
                                            'type' => 'stacked_chart',
                                            'labels' => $target_legend,
                                            'legend' => ["Achieved", "Remaining"],
                                            'data' => $target_data,
                                            'barColors' => ["#FFC300", "#DFE4EA"]
                                        ],
                                        [
                                            'name' => 'Testimonial Type',
                                            'type' => 'bar_chart',
                                            'labels' => ['Video', 'Audio', 'Image', 'Text', 'Questionnaires'],
                                            'datasets' => [
                                                ['data' => $testimonial_type_count]
                                            ]
                                        ],
                                        [
                                            'name' => 'Feedback Type',
                                            'type' => 'bar_chart',
                                            'labels' => ['Video', 'Audio', 'Image', 'Text', 'Questionnaires'],
                                            'datasets' => [
                                                ['data' => $feedback_type_count]
                                            ]
                                        ],
                                        [
                                            'name' => 'Testimonial Rating',
                                            'type' => 'bar_chart',
                                            'labels' => $rating_count_legend,
                                            'datasets' => [
                                                ['data' => $testimonial_rating_count]
                                            ]
                                        ],
                                        [
                                            'name' => 'Feedback Rating',
                                            'type' => 'bar_chart',
                                            'labels' => $rating_count_legend,
                                            'datasets' => [
                                                ['data' => $feedback_rating_count]
                                            ]
                                        ]
                                ]
                    ];

            return $this->sendResponse('success', 'Dashboard', $data, 200);

        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getEarning(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_company_id' => 'required',
                'emp_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            $totalEarning = $this->salesRepDashboardService->totalEarning($request->emp_id, $request->start_date, $request->end_date);

            return $this->sendResponse('success', 'Total Earning', $totalEarning, 200);

        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }
}
