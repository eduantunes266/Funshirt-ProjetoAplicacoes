<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;

/**
 * Política de Autorização para Imagens de T-Shirt.
 */
class TshirtImagePolicy
{
    /**
     * Determina quem pode ver o ficheiro privado de uma imagem.
     * Se não for personalizada (ou seja, é do catálogo), toda a gente vê.
     * Se for personalizada, apenas o cliente dono, funcionários ou administradores podem ver.
     */
    public function view(User $user, TshirtImage $image): bool
    {
        if (! $image->isCustom()) {
            return true;
        }

        return $user->id === $image->customer_id
            || $user->isEmployee()
            || $user->isAdmin();
    }

    /**
     * Determina quem pode atualizar uma imagem personalizada.
     * Apenas o cliente dono pode atualizar a sua própria imagem personalizada.
     */
    public function update(User $user, TshirtImage $image): bool
    {
        return $image->isCustom() && $user->id === $image->customer_id;
    }

    /**
     * Determina quem pode apagar uma imagem personalizada.
     * Apenas o cliente dono pode apagar a sua imagem.
     */
    public function delete(User $user, TshirtImage $image): bool
    {
        return $image->isCustom() && $user->id === $image->customer_id;
    }
}
