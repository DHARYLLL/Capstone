<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatFeedback extends Model
{
    use HasFactory;

    protected $table = 'bot_feedbacks';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'chat_message_id',
        'rating',
        'comment',
    ];

    /**
     * Get the company that owns the business unit.
     *
     * @return BelongsTo<ChatMessage, $this>
     */
    public function chatMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class);
    }
}
