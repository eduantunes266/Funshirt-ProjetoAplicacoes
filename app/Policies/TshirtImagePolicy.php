<?php

namespace App\Policies;

use App\Models\TshirtImage;
use App\Models\User;

class TshirtImagePolicy
{

    public function view(User $user, TshirtImage $image): bool
    {
        if (! $image->isCustom()) {
            return true;
        }

        return $user->id === $image->customer_id
            || $user->isEmployee()
            || $user->isAdmin();
    }

    public function update(User $user, TshirtImage $image): bool
    {
        return $image->isCustom() && $user->id === $image->customer_id;
    }

    public function delete(User $user, TshirtImage $image): bool
    {
        return $image->isCustom() && $user->id === $image->customer_id;
    }
}
