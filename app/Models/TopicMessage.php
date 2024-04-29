<?php

namespace App\Models;

use App\Events\MessagePosted;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int $id
 * @property int $topic_id
 * @property int $sender_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $sender
 * @property-read \App\Models\Topic $topic
 * @method static \Database\Factories\TopicMessageFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage whereTopicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TopicMessage extends Model
{
    use HasFactory;

    protected $fillable = ['content'];

    protected $dispatchesEvents = [
        'created' => MessagePosted::class,
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Models\Topic
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|\App\Models\User
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
