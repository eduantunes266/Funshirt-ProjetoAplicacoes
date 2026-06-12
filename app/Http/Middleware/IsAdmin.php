<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para garantir que o utilizador é um Administrador ativo.
 */
class IsAdmin
{
    /**
     * Interceta o pedido antes de chegar ao Controller.
     * Verifica se existe utilizador autenticado, se o tipo é Administrador ('A') e se a conta não está bloqueada.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Se passar nas verificações de segurança, o pedido segue em frente
        if ($user && $user->user_type === 'A' && ! $user->blocked) {
            return $next($request);
        }

        // Caso contrário, mostra um erro 403 (Acesso Negado)
        abort(403);
    }
}