<?php

namespace Modules\Vendor\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Password;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Region;
use App\Models\Country;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\Response;

class ResponseController extends Controller
{
    //////////////////////////////
    // Step One - Existing User //
    /////////////////////////////

    public function stepOne(Request $request)
    {
        $request->session()->forget('nic');
        $request->session()->forget('customer');
        $request->session()->forget('response');

        return view('vendor::salesrep.response.step-one');
    }

    public function postStepOne(Request $request)
    {
        $validatedData = $request->validate([
            'nic' => 'required',
        ]);

        $request->session()->put('nic', $validatedData);

        return redirect()->route('response.step-two');
    }

    ////////////////////////////
    // Step Two - Create User //
    ///////////////////////////


    public function stepTwo(Request $request)
    {
        if (empty($request->session()->get('nic'))) {
            return redirect()->route('response.step-one');
        } else {
            $nic = $request->session()->get('nic');
            $data['customer'] = User::where('nic', $nic)->first();

            if (isset($data['customer'])) {
                $request->session()->put('customer', $data['customer']);
            }
        }

        $data['regions'] = Region::get();
        $data['countries'] = Country::get();
        $data['loadCountriesUrl'] = route('load-countries');

        return view('vendor::salesrep.response.step-two', $data);
    }

    public function postStepTwo(Request $request)
    {
        if (empty($request->session()->get('customer'))) {
            $validatedData = $request->validate([
                'name' => 'required',
                'last_name' => 'required',
                'nic' => 'required|unique:users',
                'email' => 'required|email|unique:users',
                'mobile' => 'required|regex:/^([0-9\+]*)$/|min:10',
                'address' => 'required',
                'region_id' => 'required',
                'country_id' => 'required',
            ]);

            $input = $validatedData;
            $input['otp_code'] = rand(1000, 9999);
            $input['status'] = 1;
            $customer = User::create($input);

            sendOtpCode($customer);

            Password::sendResetLink($request->only(['email']));

            $request->session()->put('customer', $customer);
        } else {
            $input['otp_code'] = rand(1000, 9999);

            $customer = User::find($request->session()->get('customer')['id']);
            $customer->update($input);

            sendOtpCode($customer);
        }

        return redirect()->route('response.step-three');
    }

    ///////////////////////////////////
    // Step Three - OTP Verification //
    //////////////////////////////////

    public function stepThree(Request $request)
    {
        if (empty($request->session()->get('customer'))) {
            return redirect()->route('response.step-one');
        }

        $data['mobile'] = $request->session()->get('customer')->mobile;

        return view('vendor::salesrep.response.step-three', $data);
    }

    public function postStepThree(Request $request)
    {
        $data['mobile'] = $request->session()->get('customer')->mobile;

        if ($request->submitbutton == 'submit') {
            $customer_id = $request->session()->get('customer')->id;
            $otp = $request->otp_code;

            $customer = User::findOrFail($customer_id);

            if ($customer->otp_code != null && $customer->otp_code == $otp) {
                $customer->otp_code = null;
                $customer->save();
                $request->session()->put('customer', $customer);

                return redirect()->route('response.step-four');
            } elseif ($customer->otp_code == null) {
                $data['error'] = 'No OTP code found';
                return view('vendor::salesrep.response.step-three', $data);
            } else {
                $data['error'] = 'OTP code does not match';
                return view('vendor::salesrep.response.step-three', $data);
            }
        } elseif ($request->submitbutton == 'resend') {
            $customer_id = $request->session()->get('customer')->id;

            $customer = User::findOrFail($customer_id);
            $customer->otp_code = rand(1000, 9999);
            if ($request->input('mobile')) {
                $data['mobile'] = $request->input('mobile');
                $customer->mobile = $request->input('mobile');
            }
            $customer->save();
            $request->session()->put('customer', $customer);

            sendOtpCode($customer);
        }

        return view('vendor::salesrep.response.step-three', $data);
    }

    /////////////////////////////////
    // Step Four - Campaign Select //
    ////////////////////////////////

    public function stepFour(Request $request)
    {
        if (empty($request->session()->get('customer'))) {
            return redirect()->route('response.step-one');
        } elseif ($request->session()->get('customer')->otp_code != Null) {
            return redirect()->route('response.step-three');
        }

        $data['products'] = Product::where('vendor_company_id', auth()->user()->vendor_company_id)->where('status', 1)->get();
        $data['loadSubproductsUrl'] = route('response.load-subproducts');
        $data['loadCampaignsUrl'] = route('response.load-campaigns');

        return view('vendor::salesrep.response.step-four', $data);
    }

    public function postStepFour(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required',
            'subproduct_id' => 'required',
            'campaign_id' => 'required'
        ]);

        $validatedData['vendor_company_id'] = auth()->user()->vendor_company_id;
        $validatedData['customer_id'] = $request->session()->get('customer')->id;
        $validatedData['emp_id'] = auth()->user()->id;
        $validatedData['input_source'] = 'Web';

        if (empty($request->session()->get('response'))) {
            $response = new Response();
            $response->fill($validatedData);
            $request->session()->put('response', $response);
        } else {
            $response = $request->session()->get('response');
            $response->fill($validatedData);
            $request->session()->put('response', $response);
        }

        return redirect()->route('response.step-five');
    }

    /////////////////////////////////
    // Step Five - Response Submit //
    ////////////////////////////////

    public function stepFive(Request $request)
    {
        if (empty($request->session()->get('response'))) {
            return redirect()->route('response.step-five');
        }

        $campaign_id = $request->session()->get('response')->campaign_id;
        $campaign = Campaign::find($campaign_id);

        $data['responseType'] = $campaign->response_type;
        $data['target'] = $campaign->target;
        $data['questionnaire'] = $campaign->questionnaires->first();

        return view('vendor::salesrep.response.step-five', $data);
    }

    public function postStepFive(Request $request)
    {
        if ($request->submitbutton == 'questionnaire') {

            $validatedData = $request->validate([
                'type' => 'required',
            ]);

            $input = $request->only('type');
            $input['response_type'] = 'Questionnaire';
            $response = $request->session()->get('response');
            $response->fill($input)->save();

            foreach ($request->answer as $key => $answer) {
                if (is_array($answer)) {
                    $answer = implode(", ", $answer); // Check box answers
                }
                if (isset($answer)) {
                    $response->responseQuestions()->create(['question_id' => $key, 'answer' => $answer]);
                }
            }
        } elseif ($request->submitbutton == 'record') {

            $validatedData = $request->validate([
                'type' => 'required',
                'video' => 'mimes:mp4,webm|max:15360|required_without_all:audio,image,text,textarea',
                'audio' => 'mimes:mp3,webm,wav|max:15360|required_without_all:video,image,text,textarea',
                'image' => 'mimes:jpg,jpeg,png,gif|max:15360|required_without_all:video,audio,text,textarea',
                'text' => 'mimes:csv,txt,xlx,xls,pdf|max:15360|required_without_all:video,audio,image,textarea',
                'textarea' => 'required_without_all:video,audio,image,text',
            ]);

            $input = $request->only('type');
            $input['response_type'] = 'Record';
            $response = $request->session()->get('response');
            $response->fill($input)->save();

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

        $request->session()->forget('customer');
        $request->session()->forget('response');

        return response()->json("Uploaded Successfully");
    }


    public function loadSubproducts(Request $request)
    {
        try {
            $product_id = $request->selected_id;

            $products = Product::findOrFail($product_id);
            $subproducts = $products->subproducts;

            $temp = [];
            foreach ($subproducts as $subproduct) {
                $obj = new \stdClass();
                $obj->id = $subproduct->id;
                $obj->name = $subproduct->subproduct_name;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function loadCampaigns(Request $request)
    {
        try {
            $subproduct_id = $request->selected_id;
            $customer_id = $request->session()->get('customer')->id;

            $campaigns = Campaign::where('status', 1)
                ->where('vendor_company_id', auth()->user()->vendor_company_id)
                ->whereDate('start_date', '<=', date("Y-m-d"))
                ->whereDate('end_date', '>=', date("Y-m-d"))
                ->whereHas('subproducts', function ($q) use ($subproduct_id) {
                    $q->where('subproduct_id', $subproduct_id);
                })
                ->whereHas('employees', function ($q) {
                    $q->where('vendor_id', auth()->user()->id);
                })
                ->whereDoesntHave('responses', function ($q) use ($customer_id) {
                    $q->where('customer_id', $customer_id)->where('status', '!=', 'reject');
                })
                ->get();

            $temp = [];
            foreach ($campaigns as $campaign) {
                $obj = new \stdClass();
                $obj->id = $campaign->id;
                $obj->name = $campaign->campaign_name;
                array_push($temp, $obj);
            }

            $data['options'] = $temp;

            $view = View::make('common.common-dropdown-options', $data)->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function videoRecord()
    {
        try {
            $view = View::make('recorders.videoRecorder')->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function audioRecord()
    {
        try {
            $view = View::make('recorders.audioRecorder')->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }

    public function imageRecord()
    {
        try {
            $view = View::make('recorders.imageRecorder')->render();
            $outPutArray = array('status' => 'success', 'message' => '', 'data' => $view, 'redirect' => '', 'notifyType' => 'modal', 'modalSize' => 'xl');
            return $outPutArray;
        } catch (\Exception $ex) {
            Log::error($ex);
            $outPutArray = array('status' => 'error', 'message' => $ex->getMessage(), 'data' => '', 'redirect' => '', 'notifyType' => 'message');
            return $outPutArray;
        }
    }
}
