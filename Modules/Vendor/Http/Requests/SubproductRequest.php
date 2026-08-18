<?php

namespace Modules\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubproductRequest extends FormRequest
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
            'subproduct_code' => 'subproduct id',
            'subproduct_name' => 'subproduct name'
        ];
    }

    public function rules()
    {
        $rules = [
            'subproduct_code' => ['required', 'string', 'max:255', 'unique:subproducts,subproduct_code,' . $this->subproduct . ',id,product_id,' . $this->product_id],
            'subproduct_name' => ['required', 'string', 'max:255', 'unique:subproducts,subproduct_name,' . $this->subproduct . ',id,product_id,' . $this->product_id],
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
