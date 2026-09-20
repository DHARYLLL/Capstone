<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'file_name', 'division', 'event', 'status', 'type',
    ];

    public static function record(
        ?int $userId,
        string $event,
        string $status,
        ?string $fileName = null,
        string $division = 'DARIV',
        string $type = 'kb',
    ): self {
        return static::query()->create([
            'user_id' => $userId,
            'event' => $event,
            'status' => $status,
            'file_name' => $fileName,
            'division' => $division,
            'type' => $type,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilterByStatus(Builder $query, ?string $filter): Builder
    {
        return match ($filter) {
            'indexed' => $query->where('event', 'Indexed'),
            'staged' => $query->where(function (Builder $query): void {
                $query->where('event', 'Staged')->orWhere('status', 'Pending');
            }),
            'failed' => $query->where(function (Builder $query): void {
                $query->where('status', 'Failed')
                    ->orWhere('event', 'like', '%failed%')
                    ->orWhere('event', 'Rejected');
            }),
            'deleted' => $query->where('event', 'Deleted'),
            default => $query,
        };
    }
}