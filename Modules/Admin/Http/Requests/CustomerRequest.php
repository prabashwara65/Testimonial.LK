<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerRequest extends FormRequest
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
                'message' => '',
                'data' => $validator->errors()->toArray(),
                'redirect' => '',
                'notifyType' => 'validation'
            ], 200)
        );
    }

    public function attributes(){

        return [
            'region_id' => 'region',
            'country_id' => 'country'
        ];

    }

    public function rules()
    {
        $rules = [
            'name' => ['required'],
            'last_name' => ['required'],
            'nic' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,'.$this->customer],
            'mobile' => ['required', 'regex:/^([0-9\+]*)$/', 'min:10'],
            'address' => ['required'],
            'region_id' => ['required'],
            'country_id' => ['required'],
            'password' => ['required_if:update_password,1', 'min:8', 'confirmed'],
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
