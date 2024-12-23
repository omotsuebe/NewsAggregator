<?php

namespace App\Services\ArticleVendorServices;

use App\Models\Article;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GuardianService implements NewsServiceInterface
{
    /**
     * Fetch articles from the Guardian API.
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
        })->get('https://content.guardianapis.com/search', array_merge($queryParams, [
            'api-key' => env('GUARDIAN_API_KEY'),
            'page' => $page,
            'page-size' => 20,
        ]));

        if ($response->failed()) {
            throw new \Exception('Failed to fetch articles from The Guardian API: '.$response->body());
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
            //'section' => 'technology',
            'show-fields' => 'headline,byline,thumbnail,bodyText',
            'order-by' => 'newest',
        ];

        $totalFetched = 0;
        $page = 1;

        do {
            $response = $this->fetchArticles($queryParams, $page);

            $articles = $response['results'] ?? [];
            $totalResults = $response['total'] ?? 0;

            foreach ($articles as $article) {
                Article::firstOrCreate(
                    ['article_id' => $article['id']],
                    [
                        'article_id' => $article['id'],
                        'title' => $article['fields']['headline'] ?? '',
                        'author' => $article['fields']['byline'] ?? '',
                        'description' => $article['fields']['bodyText'] ?? '',
                        'url' => $article['webUrl'] ?? '',
                        'category' => $article['sectionName'] ?? 'General',
                        'source' => 'Guardian',
                        'published_at' => Carbon::parse($article['webPublicationDate']),
                    ]
                );
            }

            $totalFetched += count($articles);
            $page++;

        } while ($totalFetched < min(env('ARTICLE_MAX', 100), $totalResults) && count($articles) > 0);

        Log::info("Total articles fetched and saved from The Guardian: {$totalFetched}");
    }
}
