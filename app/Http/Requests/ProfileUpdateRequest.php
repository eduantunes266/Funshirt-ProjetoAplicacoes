<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Pedido de Validação para a atualização do perfil de um utilizador (Nome, Email, Foto, etc).
 */
class ProfileUpdateRequest extends FormRequest
{
    /**
     * Apenas Clientes e Administradores (não-Funcionários) podem atualizar este perfil desta forma?
     * Verifica se não é um funcionário (no sistema pode estar definido que os funcionários não alteram perfil ou o fazem noutro sítio).
     */
    public function authorize(): bool
    {
        return $this->user()->user_type !== 'F';
    }

    /**
     * Define as regras para a atualização do perfil de utilizador.
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
                // O email tem de ser único na tabela de utilizadores, ignorando o próprio ID
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'gender' => ['required', 'in:M,F'],
            'photo' => ['nullable', 'image', 'max:4096'],
        ];
    }

    /**
     * Mensagens de erro personalizadas.
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
