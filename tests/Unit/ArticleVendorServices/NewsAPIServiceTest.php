<?php

namespace Tests\Unit\ArticleVendorServices;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\ArticleVendorServices\NewsAPIService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class NewsAPIServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchArticlesSuccess(): void
    {
        Http::fake([
            'https://newsapi.org/v2/everything*' => Http::response([
                'articles' => [
                    ['url' => '1', 'title' => 'Test Article', 'author' => 'Author', 'description' => 'Description', 'publishedAt' => '2023-10-01T00:00:00Z'],
                ],
                'totalResults' => 1
            ], 200)
        ]);

        $service = new NewsAPIService();
        $articles = $service->fetchArticles();

        $this->assertNotEmpty($articles);
        $this->assertEquals('1', $articles['articles'][0]['url']);
    }

    public function testFetchArticlesFailure(): void
    {
        Http::fake([
            'https://newsapi.org/v2/everything*' => Http::response([], 500)
        ]);

        $this->expectException(\Exception::class);

        $service = new NewsAPIService();
        $service->fetchArticles();
    }

    public function testSaveArticles(): void
    {
        Http::fake([
            'https://newsapi.org/v2/everything*' => Http::response([
                'articles' => [
                    ['url' => '1', 'title' => 'Test Article', 'author' => 'Author', 'description' => 'Description', 'publishedAt' => '2023-10-01T00:00:00Z'],
                ],
                'totalResults' => 1
            ], 200)
        ]);

        Log::shouldReceive('info')->once()->with('Total articles fetched and saved from the NewsAPI: 1');

        $service = new NewsAPIService();
        $service->saveArticles();

        $this->assertDatabaseHas('articles', [
            'article_id' => '1',
            'title' => 'Test Article',
            'author' => 'Author',
            'description' => 'Description',
            'url' => '1', // Update this line to match the actual value in the database
            'category' => 'Sports',
            'source' => 'News API',
            'published_at' => Carbon::parse('2023-10-01T00:00:00Z'),
        ]);
    }
}
