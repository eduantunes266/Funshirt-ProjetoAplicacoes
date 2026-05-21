<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Funcionarios (tipo F) nao tem acesso a edicao do perfil.
     */
    public function authorize(): bool
    {
        return $this->user()->user_type !== 'F';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'gender' => ['required', 'in:M,F'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'gender.in' => 'Selecione um genero valido.',
            'photo.image' => 'O ficheiro carregado tem de ser uma imagem.',
            'photo.max' => 'A imagem nao pode exceder os 4 MB.',
        ];
    }
}
