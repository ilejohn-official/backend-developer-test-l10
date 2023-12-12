<?php

namespace App\Models;

use App\Enums\AchievementType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identifier',
        'name',
        'order_position',
        'type',
        'unlock_count',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => AchievementType::class,
    ];

    /**
     * Scope a query to only include achievements of a given type.
     */
    public function scopeOfType(Builder $query, AchievementType $type): void
    {
        $query->where('type', $type);
    }
}
