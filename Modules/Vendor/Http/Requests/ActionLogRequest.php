<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ActionLogRequest extends FormRequest
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
            'form.start_date' => 'start date',
            'form.end_date' => 'end date'
        ];
    
    }

    public function rules()
    {
        return [
            'form.start_date' => 'required|date_format:Y-m-d',
            'form.end_date' => 'required|date_format:Y-m-d|after_or_equal:form.start_date',
        ];
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
