<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para a criação de uma nova Imagem de T-shirt (do Catálogo).
 */
class StoreTshirtRequest extends FormRequest
{
    /**
     * Apenas os Administradores podem adicionar imagens ao catálogo.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Define as regras: nome é obrigatório, categoria opcional, e a imagem é obrigatória com um limite de 4MB.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:4096'],
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'A imagem da t-shirt e obrigatoria.',
            'image.max' => 'A imagem nao pode ultrapassar 4MB.',
        ];
    }
}
