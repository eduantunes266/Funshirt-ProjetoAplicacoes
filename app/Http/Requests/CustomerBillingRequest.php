<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para os dados de faturação e pagamento de um Cliente.
 */
class CustomerBillingRequest extends FormRequest
{
    /**
     * Determina se o utilizador tem permissão para alterar dados de faturação.
     */
    public function authorize(): bool
    {
        return $this->user()->user_type === 'C';
    }

    /**
     * Define as regras de validação para a faturação do cliente.
     */
    public function rules(): array
    {
        $rules = [
            'nif' => ['nullable', 'digits:9'],
            'address' => ['nullable', 'string', 'max:255'],
            // Se preencher a referência, tem de preencher o tipo e vice-versa
            'default_payment_type' => ['nullable', 'required_with:default_payment_ref', 'in:Visa,PayPal,MB WAY'],
            'default_payment_ref' => ['nullable', 'required_with:default_payment_type', 'string', 'max:255'],
        ];

        // Validação específica baseada no tipo de pagamento padrão escolhido
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

    /**
     * Mensagens de erro em português para os erros de validação.
     */
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
