<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTshirtRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

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
