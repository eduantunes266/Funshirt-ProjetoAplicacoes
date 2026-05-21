<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Descarregar o recibo PDF de uma encomenda.
     *
     * O acesso e exclusivo ao cliente dono da encomenda e aos administradores.
     * Funcionarios, utilizadores anonimos e outros clientes nao tem acesso.
     */
    public function downloadReceipt(User $user, Order $order): bool
    {
        return $user->isAdmin() || $user->id === $order->customer_id;
    }
}
