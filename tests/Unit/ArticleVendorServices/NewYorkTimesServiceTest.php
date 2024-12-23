<?php

namespace Tests\Unit\ArticleVendorServices;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ArticleVendorServices\NewYorkTimesService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class NewYorkTimesServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchArticlesSuccess(): void
    {
        Http::fake([
            'https://api.nytimes.com/svc/search/v2/articlesearch.json*' => Http::response([
                'response' => [
                    'docs' => [
                        ['_id' => '1', 'headline' => ['main' => 'Test Article'], 'byline' => ['original' => 'Author'], 'abstract' => 'Description', 'web_url' => 'http://example.com', 'news_desk' => 'Technology', 'pub_date' => '2023-10-01T00:00:00Z'],
                    ],
                    'meta' => ['hits' => 1]
                ]
            ], 200)
        ]);

        $service = new NewYorkTimesService();
        $articles = $service->fetchArticles();

        $this->assertNotEmpty($articles);
        $this->assertEquals('1', $articles['docs'][0]['_id']);
    }

    public function testFetchArticlesFailure(): void
    {
        Http::fake([
            'https://api.nytimes.com/svc/search/v2/articlesearch.json*' => Http::response([], 500)
        ]);

        $this->expectException(\Exception::class);

        $service = new NewYorkTimesService();
        $service->fetchArticles();
    }

    public function testSaveArticles(): void
    {
        Http::fake([
            'https://api.nytimes.com/svc/search/v2/articlesearch.json*' => Http::response([
                'response' => [
                    'docs' => [
                        ['_id' => '1', 'headline' => ['main' => 'Test Article'], 'byline' => ['original' => 'Author'], 'abstract' => 'Description', 'web_url' => 'http://example.com', 'news_desk' => 'Technology', 'pub_date' => '2023-10-01T00:00:00Z'],
                    ],
                    'meta' => ['hits' => 1]
                ]
            ], 200)
        ]);

        Log::shouldReceive('info')->once()->with('Total articles fetched and saved from The New York Times: 1');

        $service = new NewYorkTimesService();
        $service->saveArticles();

        $this->assertDatabaseHas('articles', [
            'article_id' => '1',
            'title' => 'Test Article',
            'author' => 'Author',
            'description' => 'Description',
            'url' => 'http://example.com',
            'category' => 'Technology',
            'source' => 'New York Times',
            'published_at' => Carbon::parse('2023-10-01T00:00:00Z'),
        ]);
    }
}
