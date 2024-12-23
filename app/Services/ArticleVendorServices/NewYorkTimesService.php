<?php

namespace App\Services\ArticleVendorServices;

use App\Models\Article;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NewYorkTimesService implements NewsServiceInterface
{
    /**
     * Fetch articles from The New York Times API.
     *
     * @param  array  $queryParams  Additional query parameters for the API request.
     * @param  int  $page  The page number for pagination starts from zero.
     * @return array The API response containing article data.
     *
     * @throws \Exception If the API request fails.
     */
    public function fetchArticles(array $queryParams = [], int $page = 1): array
    {
        $response = Http::retry(3, 2000, function ($exception, $request) {
            return $exception instanceof RequestException || $request->failed();
        })->get('https://api.nytimes.com/svc/search/v2/articlesearch.json', array_merge($queryParams, [
            'api-key' => env('NEW_YORK_TIMES_API_KEY'),
            'page' => $page,
        ]));

        if ($response->failed()) {
            throw new \Exception('Failed to fetch articles from The New York Times API: '.$response->body());
        }

        return $response->json()['response'] ?? [];
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
            //'q' => 'technology',
            'begin_date' => Carbon::today()->format('Ymd'),
            'end_date' => Carbon::today()->format('Ymd'),
            'sort' => 'newest',
        ];

        $totalFetched = 0;
        $page = 0;

        do {
            $response = $this->fetchArticles($queryParams, $page);

            $articles = $response['docs'] ?? [];
            $totalResults = $response['meta']['hits'] ?? 0;

            foreach ($articles as $article) {
                Article::firstOrCreate(
                    ['article_id' => $article['_id']],
                    [
                        'article_id' => $article['_id'],
                        'title' => $article['headline']['main'] ?? '',
                        'author' => $article['byline']['original'] ?? '',
                        'description' => $article['abstract'] ?? '',
                        'url' => $article['web_url'] ?? '',
                        'category' => $article['news_desk'] ?? $article['section_name'] ?? 'General',
                        'source' => 'New York Times',
                        'published_at' => Carbon::parse($article['pub_date']),
                    ]
                );
            }

            $totalFetched += count($articles);
            $page++;

        } while ($totalFetched < min(env('ARTICLE_MAX', 100), $totalResults) && count($articles) > 0);

        Log::info("Total articles fetched and saved from The New York Times: {$totalFetched}");
    }
}
