<?php

namespace App\Actions\Topic;

use App\Actions\Bases\ActionBase;
use App\Models\TopicMessage;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method \Illuminate\Contracts\Pagination\LengthAwarePaginator execute(array $data = [])
 */
class GetTopicMessages extends ActionBase
{
    protected function setParameters(array $data = []): void
    {
        $this->data = [
            'search' => $data['search'] ?? '',
            'per_page' => $data['per_page'] ?? 10,
            'page' => $data['page'] ?? 1,
        ];
    }

    protected function main()
    {
        return TopicMessage::query()
            ->when(
                $this->data['search'],
                fn (Builder $q) => $q->where('content', 'like', "%{$this->data['search']}%")
            )
            ->paginate(
                perPage: $this->data['per_page'],
                page: $this->data['page']
            );
    }
}
