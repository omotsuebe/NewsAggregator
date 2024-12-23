<?php

namespace Tests\Unit\ArticleVendorServices;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\ArticleVendorServices\GuardianService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class GuardianServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testFetchArticlesSuccess(): void
    {
        Http::fake([
            'https://content.guardianapis.com/search*' => Http::response([
                'response' => [
                    'results' => [
                        ['id' => '1', 'fields' => ['headline' => 'Test Article', 'byline' => 'Author', 'bodyText' => 'Description'], 'webUrl' => 'http://example.com', 'sectionName' => 'Technology', 'webPublicationDate' => '2023-10-01T00:00:00Z'],
                    ],
                    'total' => 1
                ]
            ], 200)
        ]);

        $service = new GuardianService();
        $articles = $service->fetchArticles();

        $this->assertNotEmpty($articles);
        $this->assertEquals('1', $articles['results'][0]['id']);
    }

    public function testFetchArticlesFailure(): void
    {
        Http::fake([
            'https://content.guardianapis.com/search*' => Http::response([], 500)
        ]);

        $this->expectException(\Exception::class);

        $service = new GuardianService();
        $service->fetchArticles();
    }

    public function testSaveArticles(): void
    {
        Http::fake([
            'https://content.guardianapis.com/search*' => Http::response([
                'response' => [
                    'results' => [
                        ['id' => '1', 'fields' => ['headline' => 'Test Article', 'byline' => 'Author', 'bodyText' => 'Description'], 'webUrl' => 'http://example.com', 'sectionName' => 'Technology', 'webPublicationDate' => '2023-10-01T00:00:00Z'],
                    ],
                    'total' => 1
                ]
            ], 200)
        ]);

        Log::shouldReceive('info')->once()->with('Total articles fetched and saved from The Guardian: 1');

        $service = new GuardianService();
        $service->saveArticles();

        $this->assertDatabaseHas('articles', [
            'article_id' => '1',
            'title' => 'Test Article',
            'author' => 'Author',
            'description' => 'Description',
            'url' => 'http://example.com',
            'category' => 'Technology',
            'source' => 'Guardian',
            'published_at' => Carbon::parse('2023-10-01T00:00:00Z'),
        ]);
    }
}
