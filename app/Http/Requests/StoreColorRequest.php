<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

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
