<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomImageRequest extends FormRequest
{
    /**
     * So clientes gerem imagens proprias (a posse e validada pela policy no controller).
     */
    public function authorize(): bool
    {
        return $this->user()?->isCustomer() ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'image.image' => 'O ficheiro tem de ser uma imagem valida.',
            'image.max' => 'A imagem nao pode ter mais de 2MB.',
        ];
    }
}
