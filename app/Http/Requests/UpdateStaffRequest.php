<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Pedido de Validação para a alteração de dados de um membro do Staff.
 */
class UpdateStaffRequest extends FormRequest
{
    /**
     * Apenas Administradores têm permissão para alterar contas de Staff.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * A palavra-passe e a foto são opcionais ao editar.
     * O e-mail não pode conflituar com outros utilizadores.
     */
    public function rules(): array
    {
        $staffId = $this->route('staff')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($staffId)],
            'user_type' => ['required', 'in:F,A'],
            'gender' => ['required', 'in:M,F'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
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
