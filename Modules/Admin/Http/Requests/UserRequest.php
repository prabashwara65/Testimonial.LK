<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'emp_id' => 'employee ID',
            'region_id' => 'region',
            'country_id' => 'country'
        ];
    
    }

    public function rules()
    {
        $rules = [
            'emp_id' => ['required','unique:admins,emp_id,'.$this->user],
            'name' => ['required'],
            'last_name' => ['required'],
            'nic' => ['required'],
            'email' => ['required', 'email', 'unique:admins,email,'.$this->user],
            'mobile' => ['required', 'regex:/^([0-9\+]*)$/', 'min:10'],
            'address' => ['required'],
            'username' => ['required', 'unique:admins,username,'.$this->user],
            'password' => ['required_if:update_password,1', 'min:8', 'confirmed'],
            'region_id' => ['required'],
            'country_id' => ['required'],
            'role' => ['required'],
        ];

        if ($this->getMethod() == 'POST') {
            $rules += [
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ];
        }

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
