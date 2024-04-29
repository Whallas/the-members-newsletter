<?php

namespace App\Events;

use App\Models\TopicMessage;

class MessagePosted
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public TopicMessage $message
    ) {
    }
}
