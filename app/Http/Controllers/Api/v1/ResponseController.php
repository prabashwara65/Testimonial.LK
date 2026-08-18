<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\v1\ApiController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

use App\Models\Branch;
use App\Models\Campaign;
use App\Models\Product;
use App\Models\Response;
use App\Models\Subproduct;

class ResponseController extends ApiController
{
    public function getBranch(Request $request)
    {
        try {

            if (isset($request->vendor_company_id)) {
                $branches = Branch::select('id', 'name')
                    ->where('vendor_company_id', $request->vendor_company_id)
                    ->where('status', 1)
                    ->get();
                return $this->sendResponse('success', 'Branches list', $branches, 200);
            }
            return $this->sendResponse('error', 'Vendor Company ID (vendor_company_id) required', [], 500);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getProduct(Request $request)
    {
        try {

            if (isset($request->vendor_company_id)) {
                $products = Product::select('id', 'product_code', 'product_name')
                    ->where('vendor_company_id', $request->vendor_company_id)
                    ->where('status', 1)
                    ->get();
                return $this->sendResponse('success', 'Products list', $products, 200);
            }
            return $this->sendResponse('error', 'Vendor Company ID (vendor_company_id) required', [], 500);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getSubproduct(Request $request)
    {
        try {

            if (isset($request->product_id)) {
                $subproducts = Subproduct::select('id', 'subproduct_code', 'subproduct_name')
                    ->where('product_id', $request->product_id)
                    ->where('status', 1)
                    ->get();

                return $this->sendResponse('success', 'Subproducts list', $subproducts, 200);
            }
            return $this->sendResponse('error', 'Product ID (product_id) required', [], 500);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getCampaign(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_company_id' => 'required',
                'emp_id' => 'required',
                'customer_id' => 'required',
                'subproduct_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            $campaigns = Campaign::where('status', 1)
                ->where('vendor_company_id', $request->vendor_company_id)
                ->whereDate('start_date', '<=', date("Y-m-d"))
                ->whereDate('end_date', '>=', date("Y-m-d"))
                ->whereHas('subproducts', function ($q) use ($request) {
                    $q->where('subproduct_id', $request->subproduct_id);
                })
                ->whereHas('employees', function ($q) use ($request) {
                    $q->where('vendor_id', $request->emp_id);
                });

            if (!count($campaigns->get()) > 0) { // Check if campaigns available
                return $this->sendResponse('error', 'Campaign not available', [], 500);
            }

            $campaigns = $campaigns->whereDoesntHave('responses', function ($q) use ($request) {
                $q->where('customer_id', $request->customer_id)->where('status', '!=', 'reject');
            })->get();

            if (count($campaigns) > 0) { // Check if customer already submit
                foreach ($campaigns as $ckey => $campaign) {
                    $data[$ckey] = $campaign->only('id', 'campaign_name');

                    if ($campaign->response_type == 'Questionnaire' || $campaign->response_type == 'Both') {
                        if (count($campaign->questionnaires) > 0) { // Check if questionnaires available

                            $questionnaire = $campaign->questionnaires->first();
                            foreach ($questionnaire->questions as $qkey => $question) {
                                $data[$ckey]['questions'][$qkey] = $question->only('id', 'questionnaire_id', 'type_id', 'question', 'required_needed', 'sub_question');

                                if ($question->type_id == 3) {
                                    foreach ($question->answers as $akey => $answer) {
                                        $data[$ckey]['questions'][$qkey]['answers'][$akey] = [
                                            'id' => $answer->id,
                                            'question_id' => $answer->question_id,
                                            'value' => $answer->value,
                                            'checkStatus' => false,
                                            'sub_questionnaire_question_id' => $answer->sub_questionnaire_question_id,
                                        ];
                                    }
                                } else {
                                    foreach ($question->answers as $akey => $answer) {
                                        $data[$ckey]['questions'][$qkey]['answers'][$akey] = $answer->only('id', 'question_id', 'value', 'sub_questionnaire_question_id');
                                    }
                                }
                            }
                        }
                    }

                    if ($campaign->response_type == 'Record' || $campaign->response_type == 'Both') {
                        if ($campaign->target->target_type == 2) { // Special Target
                            $data[$ckey]['records'] = $campaign->target->only('video_type', 'audio_type', 'image_type', 'text_type');
                        } else { // Common Target
                            $data[$ckey]['records'] = ['video_type' => 3, 'audio_type' => 3, 'image_type' => 3, 'text_type' => 3];
                        }
                    }
                }

                return $this->sendResponse('success', 'Campaign', $data, 200);
            }

            return $this->sendResponse('error', 'Already submitted', [], 500);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function createResponse(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vendor_company_id' => 'required',
                'type' => 'required',
                'customer_id' => 'required',
                'emp_id' => 'required',
                'campaign_id' => 'required',
                'product_id' => 'required',
                'subproduct_id' => 'required',
                'response_type' => 'required',
                'video' => 'mimes:mp4,webm,mov|max:15360',
                'audio' => 'mimes:mp3,webm,wav|max:15360',
                'image' => 'mimes:jpg,jpeg,png,gif|max:15360',
                'text' => 'mimes:csv,txt,xlx,xls,pdf|max:15360',
            ]);

            if ($validator->fails()) {
                return $this->sendResponse('error', $validator->messages(), [], 500);
            }

            $input = $request->only(['vendor_company_id', 'type', 'customer_id', 'emp_id', 'campaign_id', 'product_id', 'subproduct_id', 'response_type', 'latitude', 'longitude', 'geo_address']);
            $input['input_source'] = 'App';
            $response = Response::create($input);

            if ($request->response_type == 'Questionnaire') {
                foreach ($request->answers as $answer) {
                    $response->responseQuestions()->create($answer);
                }
            } elseif ($request->response_type == 'Record') {
                $paths = [];
                if ($request->file('video')) $paths['video'] = $request->file('video')->store('records/videos', 'public');
                if ($request->file('audio')) $paths['audio'] = $request->file('audio')->store('records/audios', 'public');
                if ($request->file('image')) $paths['image'] = $request->file('image')->store('records/images', 'public');
                if ($request->file('text')) $paths['text'] = $request->file('text')->store('records/texts', 'public');
                if ($request->textarea) {
                    $filename = 'records/texts/' . uniqid('text_', true) . '.txt';
                    Storage::disk('local')->put('public/' . $filename, $request->textarea);

                    $paths['text'] = $filename;
                }

                $response->responseRecord()->create($paths);
            }


            return $this->sendResponse('success', 'Respose Saved', [], 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }
}
