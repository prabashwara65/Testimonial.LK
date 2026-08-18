<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RewardRequest extends FormRequest
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
            'reward_code' => 'reward ID',
        ];

    }

    public function rules()
    {
        $rules = [
            'date' => ['required'],
            'reward_code' => ['required'],
            'reward_type' => ['required'],
            'discount' => ['required_if:reward_type,discount', 'nullable'],
            'gift' => ['required_if:reward_type,gift']
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
