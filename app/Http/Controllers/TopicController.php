<?php

namespace App\Http\Controllers;

use App\Actions\Topic\CreateNewTopic;
use App\Actions\Topic\GetTopics;
use App\Http\Resources\TopicResource;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;

#[Group("Listas de interesse")]
class TopicController extends Controller
{
    use ValidatesRequests;

    /**
     * Ver todas as listas de interesse
     *
     * @queryParam search string Filtro de busca
     * @queryParam page int Número da página Example: 1
     * @queryParam per_page int Itens por página Example: 1
     */
    public function index(Request $request, GetTopics $getTopics)
    {
        return TopicResource::collection(
            $getTopics->execute($request->all())
        );
    }

    /**
     * Criar uma nova lista de interesse
     */
    public function store(Request $request, CreateNewTopic $createNewTopic)
    {
        $data = $this->validate($request, [
            'title' => 'required|string|max:255',
        ]);

        return new TopicResource(
            $createNewTopic->execute($request->user(), $data)
        );
    }
}
