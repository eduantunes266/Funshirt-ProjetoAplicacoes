<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * O checkout e exclusivo para clientes autenticados (enunciado G4).
     * Administradores e funcionarios nao tem acesso.
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $rules = [
            'nif' => ['required', 'digits:9'],
            'address' => ['required', 'string', 'max:255'],
            'payment_type' => ['required', 'in:Visa,PayPal,MB WAY'],
            'payment_ref' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];

        // A referencia de pagamento e validada conforme o tipo escolhido.
        switch ($this->input('payment_type')) {
            case 'Visa':
                $rules['payment_ref'][] = 'regex:/^4\d{15}$/';
                break;
            case 'PayPal':
                $rules['payment_ref'][] = 'email';
                break;
            case 'MB WAY':
                $rules['payment_ref'][] = 'regex:/^9\d{8}$/';
                break;
        }

        return $rules;
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nif.digits' => 'O NIF tem de ter exatamente 9 digitos.',
            'payment_ref.regex' => 'A referencia nao corresponde ao formato do tipo de pagamento escolhido.',
            'payment_ref.email' => 'Para PayPal, a referencia tem de ser um email valido.',
        ];
    }
}
