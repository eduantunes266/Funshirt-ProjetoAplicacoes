<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateStaffRequest extends FormRequest
{
    /**
     * So administradores podem alterar contas de staff.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $staffId = $this->route('staff')->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($staffId)],
            'user_type' => ['required', 'in:F,A'],
            'gender' => ['required', 'in:M,F'],
            // Em branco mantem a palavra-passe atual.
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_type.in' => 'O tipo de conta tem de ser Funcionario ou Administrador.',
            'gender.in' => 'Selecione um genero valido.',
        ];
    }
}
