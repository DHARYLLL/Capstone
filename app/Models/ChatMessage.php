<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'chat_session_id',
        'sender_type',
        'message_text',
    ];

    /**
     * Get the company that owns the business unit.
     *
     * @return BelongsTo<ChatSession, $this>
     */
    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }

    /**
     * Get the knowledge entries for the business unit.
     *
     * @return HasMany<ChatFeedback, $this>
     */
    public function chatFeedback(): HasMany
    {
        return $this->hasMany(ChatFeedback::class);
    }
}
