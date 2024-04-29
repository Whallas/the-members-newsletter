<?php

namespace App\Policies;

use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    public function delete(User $user, Subscription $subscription): bool
    {
        return $user->id === $subscription->user_id || $user->isAdmin();
    }
}
