<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para a alteração dos Preços Globais da Loja.
 */
class UpdatePriceRequest extends FormRequest
{
    /**
     * Apenas Administradores têm permissão para alterar preços.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Validações de preços base e valores de desconto. O desconto não pode exceder o valor normal.
     */
    public function rules(): array
    {
        return [
            'unit_price_catalog' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'unit_price_catalog_discount' => ['required', 'numeric', 'min:0', 'max:9999.99', 'lte:unit_price_catalog'],
            'unit_price_own' => ['required', 'numeric', 'min:0', 'max:9999.99'],
            'unit_price_own_discount' => ['required', 'numeric', 'min:0', 'max:9999.99', 'lte:unit_price_own'],
            'qty_discount' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'unit_price_catalog_discount.lte' => 'O preco com desconto do catalogo nao pode ser superior ao preco normal.',
            'unit_price_own_discount.lte' => 'O preco com desconto das personalizadas nao pode ser superior ao preco normal.',
        ];
    }
}
