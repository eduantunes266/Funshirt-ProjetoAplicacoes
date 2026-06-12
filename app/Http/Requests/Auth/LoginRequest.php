<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Pedido de Validação para o processo de Autenticação (Login).
 */
class LoginRequest extends FormRequest
{
    /**
     * Qualquer utilizador (não autenticado) pode tentar fazer o login.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras básicas: e-mail e palavra-passe obrigatórios.
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Tenta autenticar o utilizador.
     * Inclui validação adicional para verificar se a conta não está bloqueada pelo administrador.
     */
    public function authenticate(): void
    {
        // Garante que não existem tentativas excessivas num curto espaço de tempo
        $this->ensureIsNotRateLimited();

        // Tenta fazer o match do e-mail e da password com a DB
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        // Se o utilizador estiver bloqueado (coluna 'blocked' = 1 na tabela users)
        if (Auth::user()->blocked) {
            Auth::logout();
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'A sua conta esta bloqueada. Contacte a administracao da FunShirt.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Protege contra ataques de "Brute Force" (Rate Limiting).
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Chave de cache para limitar tentativas baseada no e-mail e no IP da pessoa.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
