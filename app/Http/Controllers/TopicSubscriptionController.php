<?php

namespace App\Http\Controllers;

use App\Actions\Topic\GetTopicSubscriptions;
use App\Actions\Topic\SubscribeUser;
use App\Http\Resources\GetTopicSubscriptionsResource;
use App\Http\Resources\TopicSubscriptionResource;
use App\Models\Subscription;
use App\Models\Topic;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Knuckles\Scribe\Attributes\Group;

#[Group("Listas de interesse")]
class TopicSubscriptionController extends Controller
{
    use AuthorizesRequests;

    /**
     * Obter usuários inscritos da lista
     */
    public function index(Topic $topic, GetTopicSubscriptions $getTopicSubscriptions)
    {
        return GetTopicSubscriptionsResource::collection(
            $getTopicSubscriptions->execute($topic)
        );
    }

    /**
     * Inscrever-se na lista
     */
    public function store(Topic $topic, SubscribeUser $subscribeUser)
    {
        return new TopicSubscriptionResource(
            $subscribeUser->execute(auth()->user(), $topic)
        );
    }

    /**
     * Cancelar inscrição
     */
    public function destroy(Subscription $subscription)
    {
        $this->authorize('delete', $subscription);

        return response()->json(null, 204);
    }
}
