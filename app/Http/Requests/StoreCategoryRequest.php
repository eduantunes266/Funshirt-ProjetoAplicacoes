<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Pedido de Validação para a criação de uma nova Categoria.
 */
class StoreCategoryRequest extends FormRequest
{
    /**
     * Apenas os Administradores podem criar categorias de catálogo.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Regras para a categoria: Nome único e imagem opcional, mas se existir deve ser num formato de imagem.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:4096'],
        ];
    }
}
