<?php

namespace App\Models;

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
}
