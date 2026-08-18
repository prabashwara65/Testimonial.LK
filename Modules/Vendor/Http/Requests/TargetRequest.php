<?php

namespace Modules\Vendor\Http\Requests;

use App\Rules\TargetValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TargetRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
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

    public function attributes()
    {

        return [
            'product_code' => 'product id',
            'product_name' => 'product name'
        ];
    }

    public function rules()
    {
        $rules = [
            'target_name' => ['required', 'max:255'],
            'target_type' => ['required'],
            'video' => [new TargetValidation('video')],
            'audio' => [new TargetValidation('audio')],
            'image' => [new TargetValidation('image')],
            'text' => [new TargetValidation('text')],
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
