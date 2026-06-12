<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação (FormRequest) para a finalização de uma compra (Checkout).
 */
class CheckoutRequest extends FormRequest
{
    /**
     * Determina se o utilizador está autorizado a fazer este pedido.
     * Só os clientes (C) podem fazer encomendas.
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * Define as regras de validação aplicáveis aos dados enviados no checkout.
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

        // Adiciona validação específica baseada no tipo de pagamento escolhido
        switch ($this->input('payment_type')) {
            case 'Visa':
                // Visa: Começa com 4 e tem exatamente 16 dígitos
                $rules['payment_ref'][] = 'regex:/^4\d{15}$/';
                break;
            case 'PayPal':
                // PayPal: Tem de ser um e-mail válido
                $rules['payment_ref'][] = 'email';
                break;
            case 'MB WAY':
                // MB WAY: Começa com 9 e tem exatamente 9 dígitos
                $rules['payment_ref'][] = 'regex:/^9\d{8}$/';
                break;
        }

        return $rules;
    }

    /**
     * Define as mensagens de erro personalizadas para este pedido.
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
