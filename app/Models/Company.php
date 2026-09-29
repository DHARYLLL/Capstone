<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Company extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'contact_email',
        'contact_phone',
        'contact_address',
        'api_key',
        'storage_quota_bytes',
        'vector_chunk_quota',
    ];

    protected $casts = [
        'storage_quota_bytes' => 'integer',
        'vector_chunk_quota' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Company $company): void {
            $company->api_key ??= 'pk_live_' . Str::random(24);
        });
    }

    /**
     * Get the business units for the company.
     *
     * @return HasMany<BusinessUnit, $this>
     */
    public function businessUnits(): HasMany
    {
        return $this->hasMany(BusinessUnit::class);
    }

    /**
     * Get all knowledge entries through the company business units.
     *
     * @return HasManyThrough<BusinessKnowledge, BusinessUnit, $this>
     */
    public function businessKnowledge(): HasManyThrough
    {
        return $this->hasManyThrough(BusinessKnowledge::class, BusinessUnit::class);
    }
}
