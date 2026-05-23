<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

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
