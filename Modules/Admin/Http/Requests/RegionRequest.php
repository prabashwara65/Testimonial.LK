<?php

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegionRequest extends FormRequest
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
            'region' => ['required', 'unique:regions,region,'.request()->route('region'), 'string', 'max:255'],
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
