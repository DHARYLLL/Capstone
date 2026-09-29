<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KnowledgeDeficit extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_feedback_id',
        'company_id',
        'business_unit_id',
        'chat_session_id',
        'chat_message_id',
        'customer_query',
        'missing_context_details',
        'status',
        'flagged_at',
    ];

    protected $casts = [
        'flagged_at' => 'datetime',
    ];

    public function feedback(): BelongsTo
    {
        return $this->belongsTo(ChatFeedback::class, 'chat_feedback_id');
    }

    public function chatMessage(): BelongsTo
    {
        return $this->belongsTo(ChatMessage::class);
    }

    public function chatSession(): BelongsTo
    {
        return $this->belongsTo(ChatSession::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}
