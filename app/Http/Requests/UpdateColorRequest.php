<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para a atualização do nome ou imagem de uma Cor de t-shirt.
 */
class UpdateColorRequest extends FormRequest
{
    /**
     * Apenas Administradores podem atualizar as cores.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * O código da cor não pode ser editado, apenas o nome e a imagem base.
     */
    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }
}
