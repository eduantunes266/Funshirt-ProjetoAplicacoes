<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

/**
 * Pedido de Validação para a criação de um novo membro do Staff (Funcionário ou Administrador).
 */
class StoreStaffRequest extends FormRequest
{
    /**
     * Apenas Administradores podem criar novas contas de Staff.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * O staff precisa de nome, email único, tipo (F ou A), género e palavra-passe segura.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'user_type' => ['required', 'in:F,A'],
            'gender' => ['required', 'in:M,F'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'user_type.in' => 'O tipo de conta tem de ser Funcionario ou Administrador.',
            'gender.in' => 'Selecione um genero valido.',
        ];
    }
}
