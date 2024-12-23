<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'title',
        'author',
        'description',
        'url',
        'category',
        'source',
        'published_at',
    ];

    public function scopeArticleBetweenDates($query, array $dates): mixed
    {
        return $query->whereBetween('created_at', [
            Carbon::parse($dates[0])->startOfDay(),
            Carbon::parse($dates[1])->endOfDay(),
        ]);
    }
}
