<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChatSession extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'company_id',
    //     'business_unit_id',
    //     'status',
    //     'assigned_user_id',
    //     'user_identifier',
    // ];
    protected $fillable = [
        'company_id',
        'business_unit_id',
        'user_identifier',
        'status',
        'handed_off_at',
    ];

    protected $casts = [
        'handed_off_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class);
    }
}
