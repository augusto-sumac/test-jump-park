<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'vehiclePlate' => 'required|string|size:7',
            'entryDateTime' => 'required|date',
            'exitDateTime' => 'nullable|date|after:entryDateTime',
            'priceType' => 'nullable|string|max:55',
            'price' => 'required|numeric|min:0',
            'userId' => 'required|exists:users,id'
        ];
    }

    public function messages()
    {
        return [
            'vehiclePlate.required' => 'A placa do veículo é obrigatória.',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors()
        ]));
    }
}
