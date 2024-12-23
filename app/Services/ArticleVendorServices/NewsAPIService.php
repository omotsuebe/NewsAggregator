<?php

namespace App\Services\ArticleVendorServices;

use App\Models\Article;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewsAPIService implements NewsServiceInterface
{
    /**
     * Fetch articles from News API.
     *
     * @param  array  $queryParams  Additional query parameters for the API request.
     * @param  int  $page  The page number for pagination start from one
     * @return array The API response containing article data.
     *
     * @throws \Exception If the API request fails.
     */
    public function fetchArticles(array $queryParams = [], int $page = 1): array
    {
        $response = Http::retry(3, 2000, function ($exception, $request) {
            return $exception instanceof RequestException || $request->failed();
        })->get('https://newsapi.org/v2/everything', array_merge($queryParams, [
            'apiKey' => env('NEWS_API_KEY'),
            'pageSize' => 20,
            'page' => $page,
        ]));

        if ($response->failed()) {
            throw new \Exception('Failed to fetch articles from NewsAPI: '.$response->body());
        }

        return $response->json();
    }

    /**
     * Fetch and save articles from The New York Times API to the local database.
     * Uses pagination to limit the number of records fetched and saved.
     *
     * @throws \Exception If fetching articles fails.
     */
    public function saveArticles(): void
    {
        $queryParams = [
            'q' => 'sports',
            //'from' => Carbon::today()->toDateString(),
            //'to' => Carbon::today()->toDateString(),
            'sortBy' => 'newest',
        ];

        $totalFetched = 0;
        $page = 1;

        do {
            $response = $this->fetchArticles($queryParams, $page);

            $articles = $response['articles'] ?? [];
            $totalResults = $response['totalResults'] ?? 0;

            foreach ($articles as $article) {
                Article::firstOrCreate(
                    ['article_id' => $article['url']],
                    [
                        'article_id' => $article['url'],
                        'title' => $article['title'],
                        'author' => $article['author'],
                        'description' => $article['description'],
                        'url' => $article['url'],
                        'category' => 'Sports',
                        'source' => 'News API',
                        'published_at' => Carbon::parse($article['publishedAt']),
                    ]
                );
            }

            $totalFetched += count($articles);
            $page++;

        } while ($totalFetched < min(env('ARTICLE_MAX', 100), $totalResults) && count($articles) > 0);

        Log::info("Total articles fetched and saved from the NewsAPI: {$totalFetched}");
    }
}
