<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsStaff
{
    /**
     * So permite acesso a funcionarios (F) e administradores (A).
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ($user->isEmployee() || $user->isAdmin())) {
            return $next($request);
        }

        abort(403);
    }
}
