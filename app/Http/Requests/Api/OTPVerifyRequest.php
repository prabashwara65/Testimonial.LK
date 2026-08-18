<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OTPVerifyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(
            response()->json([
                'status' => 'error',
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()->toArray(),
            ], 200)
        ); 
    } 

    public function attributes(){

        return [
            'otp_code' => 'OTP code'
        ];
    
    }

    public function rules()
    {
        $rules = [
            'customer_id' => ['required'],
            'otp_code' => ['required'],
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
