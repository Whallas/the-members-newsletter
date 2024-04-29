<?php

namespace App\Http\Controllers;

use App\Actions\Topic\CommentTopic;
use App\Actions\Topic\GetTopicMessages;
use App\Http\Resources\TopicMessageResource;
use App\Models\Topic;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;

#[Group("Listas de interesse")]
class TopicMessageController extends Controller
{
    use ValidatesRequests;

    /**
     * Ver todas as mensagens da lista de interesse
     *
     * @queryParam search string Filtro de busca
     * @queryParam page int Número da página Example: 1
     * @queryParam per_page int Itens por página Example: 1
     */
    public function index(Request $request, GetTopicMessages $getMessages)
    {
        return TopicMessageResource::collection(
            $getMessages->execute($request->all())
        );
    }

    /**
     * Postar comentário
     */
    public function store(Topic $topic, Request $request, CommentTopic $commentTopic)
    {
        $data = $this->validate($request, [
            'comment' => 'required|string|max:255',
        ]);

        return new TopicMessageResource(
            $commentTopic->execute($topic, $request->user(), $data)
        );
    }
}
