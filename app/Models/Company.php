<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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
    ];

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
