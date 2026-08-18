<?php

namespace App\Http\Requests\Api;

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
                'message' => 'The given data was invalid.',
                'data' => $validator->errors()->toArray(),
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
            'email' => ['required', 'email', 'unique:users'],
            'mobile' => ['required', 'regex:/^([0-9\+]*)$/', 'min:10'],
            'address' => ['required'],
            'region_id' => ['required'],
            'country_id' => ['required'],
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
