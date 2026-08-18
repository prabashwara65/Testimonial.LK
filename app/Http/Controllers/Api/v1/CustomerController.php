<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use App\Http\Requests\Api\CustomerRequest;
use App\Http\Requests\Api\OTPVerifyRequest;
use App\Http\Requests\Api\OTPResendRequest;

use App\Models\Region;
use App\Models\Country;
use App\Models\User;


class CustomerController extends ApiController
{
    public function getRegion()
    {
        try {

            $regions = Region::select('id', 'region')->get();
            return $this->sendResponse('success', 'Regions list', $regions, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getCountry(Request $request)
    {
        try {

            if (isset($request->region_id)) {
                $countries = Country::select('id', 'country')
                    ->where('region_id', $request->region_id)
                    ->get();

                return $this->sendResponse('success', 'Countries list', $countries, 200);
            }
            return $this->sendResponse('error', 'Region ID (region_id) required', [], 500);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function getCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nic' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse('error', $validator->messages(), [], 500);
        }

        try {
            $nic = $request->only(['nic']);
            $customer = User::where('nic', $nic)->first();

            if (!empty($customer)) {
                return $this->sendResponse('success', 'Customer Details', $customer, 200);
            } else {
                return $this->sendResponse('error', 'Customer Not Found', [], 500);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function createCustomer(CustomerRequest $request)
    {
        try {

            $input = $request->only(['name', 'last_name', 'nic', 'email', 'mobile', 'address', 'address_line1', 'address_line2', 'country_id', 'region_id']);
            $input['otp_code'] = rand(1000, 9999);
            $input['status'] = 1;
            $customer = User::create($input);

            sendOtpCode($customer);

            Password::sendResetLink($request->only(['email']));

            return $this->sendResponse('success', 'Customer registration successful', $customer, 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function otpVerify(OTPVerifyRequest $request)
    {
        try {
            $customer_id = $request->input('customer_id');
            $otp = $request->input('otp_code');

            //$customer = Customer::findOrFail($customer_id);
            $customer = User::findOrFail($customer_id);

            if ($customer->otp_code != null && $customer->otp_code == $otp) {
                $customer->otp_code = null;
                $customer->save();

                return $this->sendResponse('success', 'OTP match', ['customer' => $customer], 200);
            } elseif ($customer->otp_code == null) {
                return $this->sendResponse('error', 'No OTP code found', [], 422);
            } else {
                return $this->sendResponse('error', 'OTP code does not match', [], 422);
            }
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }

    public function otpResend(OTPResendRequest $request)
    {
        try {
            $customer_id = $request->input('customer_id');

            $customer = User::findOrFail($customer_id);
            $customer->otp_code = rand(1000, 9999);
            if ($request->input('mobile')) {
                $customer->mobile = $request->input('mobile');
            }
            $customer->save();

            sendOtpCode($customer);

            return $this->sendResponse('success', 'Successfully sent the OTP code', ['mobile' => $customer->mobile], 200);
        } catch (\Exception $ex) {
            Log::error($ex);
            return $this->sendResponse('error', $ex->getMessage(), [], 500);
        }
    }
}
