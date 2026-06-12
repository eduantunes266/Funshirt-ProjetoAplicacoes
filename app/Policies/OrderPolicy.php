<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

/**
 * Política de Autorização para Encomendas.
 */
class OrderPolicy
{
    /**
     * Determina se um utilizador pode visualizar os detalhes de uma encomenda específica.
     * Apenas o próprio cliente que fez a encomenda a pode ver nesta rota específica.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->customer_id;
    }

    /**
     * Determina se um utilizador pode descarregar o recibo de uma encomenda.
     * Pode ser feito pelo Administrador ou pelo próprio Cliente que fez a encomenda.
     */
    public function downloadReceipt(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->id === $order->customer_id;
    }
}
