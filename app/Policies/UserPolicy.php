<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Consultar a area de gestao de contas.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Criar novas contas (funcionarios ou administradores).
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Alterar uma conta.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin();
    }

    /**
     * Remover uma conta - um admin nunca pode remover-se a si proprio.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }

    /**
     * Bloquear/desbloquear uma conta - um admin nunca pode bloquear-se a si proprio.
     */
    public function block(User $user, User $model): bool
    {
        return $user->isAdmin() && $user->id !== $model->id;
    }
}
