<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * This method converts the Article model instance into an associative
     * array, making it suitable for JSON response. It includes details
     * such as the article ID, title, author, description, and publication
     * information.
     *
     * @param  \Illuminate\Http\Request  $request  The incoming request instance.
     * @return array<string, mixed> The transformed article data as an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'article_id' => $this->article_id,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'url' => $this->url,
            'category' => $this->category,
            'source' => $this->source,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
