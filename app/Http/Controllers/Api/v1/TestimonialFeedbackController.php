<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\ApiController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

use App\Models\Response;
use App\Models\Campaign;

class TestimonialFeedbackController extends ApiController
{
    public function getAllCampaigns(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_company_id' => 'required',
                'emp_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            $campaigns = Campaign::select('id', 'campaign_name')->where('status', 1)
            ->where('vendor_company_id', $request->vendor_company_id)
            ->whereHas('employees', function ($q) use ($request){
                $q->where('vendor_id', $request->emp_id);
            })->get();

            return $this->sendResponse('success', 'All Campaigns', $campaigns, 200);

        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getResponsesList(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_company_id' => 'required',
                'type' => 'required',
                'emp_id' => 'required',
                'status' => 'required',
                'campaign_id' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'offset' => 'required',
                'limit' => 'required',
            ]);
             
            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            $responses = Response::where('vendor_company_id', '=', $request->vendor_company_id)
                            ->where('type', '=', $request->type)
                            ->where('emp_id', '=', $request->emp_id)
                            ->where('campaign_id', '=', $request->campaign_id)
                            ->where('status', '=', $request->status)
                            ->whereRaw("DATE(created_at) BETWEEN '".$request->start_date."' AND '".$request->end_date."' ")
                            ->orderBy('created_at', 'DESC')
                            ->offset($request->offset)->limit($request->limit)->get();

            $data = [];
            foreach ($responses as $key => $response) {
                $data[$key] = [
                    'id' => $response->id,
                    'type' => $response->type,
                    'dateTime' => $response->created_at->format('Y-m-d h:i A'),
                    'customerName' => $response->user->name." ".$response->user->last_name,
                    'rating' => $response->rating,
                    'status' => $response->status
                ];
            }
            
            return $this->sendResponse('success', 'Responses List', $data, 200);

        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    } 

    public function getResponse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'response_id' => 'required',
            ]);
             
            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            $response = Response::find($request->response_id);

            $data['details'] = [
                'id' => $response->id,
                'customerName' => $response->user->name." ".$response->user->last_name,
                'nic' => $response->user->nic,
                'mobile' => $response->user->mobile,
                'email' => $response->user->email,
                'address' => $response->user->address." ".$response->user->address_line1." ".$response->user->address_line2,
                'country' => $response->user->country->country,
                'region' => $response->user->region->region,
                'latitude' => $response->latitude,
                'longitude' => $response->longitude,
                'geo_address' => $response->geo_address,

                'product' => $response->product->product_name,
                'subproduct' => $response->subproduct->subproduct_name,
                'rating' => $response->rating,
                'reject_reason' => $response->reject_reason,
                'status' => $response->status,
            ];

            if($response->response_type == 'Questionnaire') {
                foreach($response->responseQuestions as $questions) {
                    $data['questionnaires'][] = [
                        'id' => $questions->id,
                        'question' => $questions->question->question,
                        'answer' => $questions->answer
                    ];
                }
            }
            else {
                $data['record'] = $response->responseRecord;
            }

            return $this->sendResponse('success', 'Responses List', $data, 200);

        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }
}