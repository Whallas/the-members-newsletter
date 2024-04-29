<?php

namespace App\Actions\Topic;

use App\Models\Subscription;
use App\Models\Topic;
use App\Models\User;

class SubscribeUser
{
    public function execute(User $user, Topic $topic): Subscription
    {
        $subscription = Subscription::query()->firstOrCreate([
            'topic_id' => $topic->id,
            'user_id' => $user->id,
        ]);

        return $subscription;
    }
}
