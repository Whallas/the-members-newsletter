<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class GetTopicSubscriptionsResource extends TopicSubscriptionResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'user' => [
                'id' => $this->user->id,
                'email' => $this->user->email,
            ],
        ];
    }
}
