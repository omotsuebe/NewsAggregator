<?php

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ShowArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_article_success(): void
    {
        $article = Article::factory()->create();

        $this->getJson('/api/v1/article/'.$article->id)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'result' => true,
                'status' => 'success',
                'message' => 'Article fetched',
                'data' => [
                    'article' => [
                        'id' => $article->id,
                        'title' => $article->title,
                        'description' => $article->description,
                    ],
                ],
            ]);
    }

    public function test_show_article_with_invalid_id(): void
    {
        $this->getJson('/api/v1/article/invalid-id')
            ->assertStatus(404)
            ->assertJson([
                'result' => false,
                'status' => 'error',
                'message' => 'No record found.',
                'errors' => [],
            ]);
    }
}
