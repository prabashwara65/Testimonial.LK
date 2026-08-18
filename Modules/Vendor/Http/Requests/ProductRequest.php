<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
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
            'product_code' => ['required', 'string', 'max:255', 'unique:products,product_code,' . $this->product . ',id,vendor_company_id,' . auth()->user()->vendor_company_id],
            'product_name' => ['required', 'string', 'max:255', 'unique:products,product_name,' . $this->product . ',id,vendor_company_id,' . auth()->user()->vendor_company_id],
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
