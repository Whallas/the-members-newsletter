<?php

namespace App\Listeners;

use App\Events\MessagePosted;
use App\Notifications\NewTopicMessageNotification;
use Illuminate\Support\Facades\Notification;

class NotifyUsersOfNewMessage
{
    /**
     * Handle the event.
     */
    public function handle(MessagePosted $event): void
    {
        Notification::send(
            $event->message->topic->subscribers,
            new NewTopicMessageNotification($event->message)
        );
    }
}
