<?php

namespace App\Policies;

use App\Models\User;

/**
 * Política de Autorização para operações sobre Utilizadores (Staff e Clientes).
 */
class UserPolicy
{
    /**
     * Determina quem pode listar utilizadores no painel de administração.
     * Apenas Administradores.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina quem pode criar utilizadores pelo painel (ex: criar novo Staff).
     * Apenas Administradores.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina quem pode atualizar os dados de outros utilizadores.
     * Apenas Administradores.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determina quem pode apagar utilizadores.
     * Apenas Administradores, e um Administrador não pode apagar-se a si próprio.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Determina quem pode bloquear ou desbloquear contas.
     * Apenas Administradores, e um Administrador não pode bloquear-se a si próprio.
     */
    public function block(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
