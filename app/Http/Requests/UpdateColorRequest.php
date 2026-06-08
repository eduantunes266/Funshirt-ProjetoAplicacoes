<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {

        return [
            'name' => ['required', 'string', 'max:255'],
            'base_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:4096'],
        ];
    }
}
