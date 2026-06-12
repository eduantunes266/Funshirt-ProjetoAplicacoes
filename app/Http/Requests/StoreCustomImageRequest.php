<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para o envio de uma Imagem Personalizada por parte de um Cliente.
 */
class StoreCustomImageRequest extends FormRequest
{
    /**
     * Apenas clientes registados podem enviar imagens personalizadas.
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * A imagem enviada pelo cliente é obrigatória e deve respeitar formato e tamanho (2MB).
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'E obrigatorio inserir uma imagem.',
            'image.image' => 'O ficheiro tem de ser uma imagem valida.',
            'image.max' => 'A imagem nao pode ter mais de 2MB.',
        ];
    }
}
