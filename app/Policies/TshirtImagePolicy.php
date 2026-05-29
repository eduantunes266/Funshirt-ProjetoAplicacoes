<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;

class TshirtImagePolicy
{
    /**
     * Ver o ficheiro da imagem.
     * Catalogo: publico. Imagem propria: so o dono + funcionarios/admin
     * (estes ultimos apenas para consulta no ambito das encomendas).
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
     * Gerir (alterar) uma imagem propria: exclusivo do dono.
     */
    public function update(User $user, TshirtImage $image): bool
    {
        return $image->isCustom() && $user->id === $image->customer_id;
    }

    /**
     * Remover uma imagem propria: exclusivo do dono.
     */
    public function delete(User $user, TshirtImage $image): bool
    {
        return $image->isCustom() && $user->id === $image->customer_id;
    }
}
