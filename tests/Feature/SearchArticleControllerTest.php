<?php

namespace Tests\Feature;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $defaultFilters = [
        'search' => 'test',
        'category' => 'news',
        'source' => 'source1',
        'author' => 'author1',
        'date_from' => '2024-01-01',
        'date_to' => '2024-12-31',
        'limit' => 10,
        'page' => 1,
    ];

    private array $articleStructure = [
        'id',
        'title',
        'author',
        'description',
        'url',
        'category',
        'source',
        'published_at',
        'created_at',
        'updated_at',
    ];

    private array $metaStructure = [
        'current_page',
        'per_page',
        'total',
        'last_page',
    ];

    public function test_successful_fetching_of_articles(): void
    {
        Article::factory()->count(10)->create();

        $this->json('GET', '/api/v1/articles', $this->defaultFilters)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'articles' => ['*' => $this->articleStructure],
                    'meta' => $this->metaStructure,
                ],
            ]);
    }

    public function test_fetching_articles_with_pagination(): void
    {
        Article::factory()->count(50)->create();

        $this->json('GET', '/api/v1/articles', ['limit' => 10, 'page' => 2])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'articles' => ['*' => $this->articleStructure],
                    'meta' => $this->metaStructure,
                ],
            ])
            ->assertJson([
                'data' => [
                    'meta' => ['current_page' => 2, 'per_page' => 10],
                ],
            ]);
    }
}
