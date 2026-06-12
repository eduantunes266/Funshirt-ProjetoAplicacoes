<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para garantir que o utilizador pertence ao Staff (Funcionário ou Administrador) e está ativo.
 */
class IsStaff
{
    /**
     * Interceta o pedido antes de chegar ao Controller.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // Verifica se o utilizador existe, não está bloqueado e tem perfil de funcionário ou administrador
        if ($user && ! $user->blocked && ($user->isEmployee() || $user->isAdmin())) {
            return $next($request);
        }

        // Caso contrário, bloqueia com acesso negado (403)
        abort(403);
    }
}
