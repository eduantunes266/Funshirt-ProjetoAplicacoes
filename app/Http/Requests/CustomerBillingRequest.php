<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerBillingRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()->user_type === 'C';
    }

    public function rules(): array
    {
        $rules = [
            'nif' => ['nullable', 'digits:9'],
            'address' => ['nullable', 'string', 'max:255'],
            'default_payment_type' => ['nullable', 'required_with:default_payment_ref', 'in:Visa,PayPal,MB WAY'],
            'default_payment_ref' => ['nullable', 'required_with:default_payment_type', 'string', 'max:255'],
        ];

        switch ($this->input('default_payment_type')) {
            case 'Visa':
                $rules['default_payment_ref'][] = 'regex:/^4\d{15}$/';
                break;
            case 'PayPal':
                $rules['default_payment_ref'][] = 'email';
                break;
            case 'MB WAY':
                $rules['default_payment_ref'][] = 'regex:/^9\d{8}$/';
                break;
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nif.digits' => 'O NIF tem de ter exatamente 9 digitos.',
            'default_payment_type.required_with' => 'Indique o tipo de pagamento.',
            'default_payment_ref.required_with' => 'Indique a referencia de pagamento.',
            'default_payment_ref.regex' => 'A referencia nao corresponde ao formato do tipo de pagamento escolhido.',
            'default_payment_ref.email' => 'Para PayPal, a referencia tem de ser um email valido.',
        ];
    }
}
