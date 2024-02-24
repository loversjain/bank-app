<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'email' => 'required|email',
        ];
    }

    public function failedValidation(Validator $validator) {

        throw new HttpResponseException(response()->json([

            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()

        ]));

    }

    public function messages()
    {
        return [

            'amount.required' => 'amount is required',
            'email.required' => 'email is required',

        ];

    }
}
