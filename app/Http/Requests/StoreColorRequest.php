<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para a criação de uma nova Cor para as t-shirts base.
 */
class StoreColorRequest extends FormRequest
{
    /**
     * Apenas os Administradores podem adicionar cores.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * O código e o nome da cor são obrigatórios.
     * Além disso, uma cor base deve ter uma imagem representativa da t-shirt com essa cor.
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'unique:colors,code'],
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'base_image.required' => 'A imagem da t-shirt base e obrigatoria.',
        ];
    }
}
