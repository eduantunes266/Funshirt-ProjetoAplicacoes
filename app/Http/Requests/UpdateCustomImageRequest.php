<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para a atualização de uma Imagem Personalizada por um Cliente.
 */
class UpdateCustomImageRequest extends FormRequest
{
    /**
     * Apenas clientes podem alterar as suas próprias imagens.
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * A nova imagem é opcional (pode querer apenas mudar o nome ou descrição).
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.image' => 'O ficheiro tem de ser uma imagem valida.',
            'image.max' => 'A imagem nao pode ter mais de 2MB.',
        ];
    }
}
