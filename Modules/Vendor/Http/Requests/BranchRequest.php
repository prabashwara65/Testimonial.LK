<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BranchRequest extends FormRequest
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

    public function rules()
    {
        $rules = [
            'branch_code' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'contact_no' => ['required', 'regex:/^([0-9\+]*)$/', 'min:10'],
            'address' => ['required', 'string'],
            'region_id' => ['required'],
            'country_id' => ['required'],
            'province_id' => ['required'],
            'district_id' => ['required'],
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
