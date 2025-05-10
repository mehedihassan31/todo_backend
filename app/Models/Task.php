<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'body',
        'user_id',
        'is_completed',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSearch(Builder $query, string $keyword = null, int $isComplete = null): Builder
    {
        return $query
            ->when($keyword ?? null, function ($q, $keyword) {
                $q->where('title', 'LIKE', "%$keyword%");
            });
    }

}
