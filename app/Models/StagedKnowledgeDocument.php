<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StagedKnowledgeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_unit_id',
        'branch',
        'original_name',
        'mime_type',
        'file_size',
        'stored_path',
        'edited_content',
        'status',
    ];

    public function businessUnit(): BelongsTo
    {
        return $this->belongsTo(BusinessUnit::class);
    }
}