<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    /**
     * Retrieve articles based on search queries and user preferences with pagination.
     */
    public function search(array $filters): LengthAwarePaginator
    {
        try {
            $query = Article::query();

            // Filter by search query (title, description)
            if (! empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%'.$filters['search'].'%')
                        ->orWhere('description', 'like', '%'.$filters['search'].'%');
                });
            }

            // Filter by category
            if (! empty($filters['category'])) {
                $query->whereRaw('LOWER(category) = ?', [strtolower($filters['category'])]);
            }

            // Filter by source
            if (! empty($filters['source'])) {
                $query->whereRaw('LOWER(source) = ?', [strtolower($filters['source'])]);
            }

            // Filter by author
            if (! empty($filters['author'])) {
                $query->whereRaw('LOWER(author) = ?', [strtolower($filters['author'])]);
            }

            // Filter by date range (from and to dates)
            if (! empty($filters['from_date']) && ! empty($filters['to_date'])) {
                $query->articleBetweenDates([$filters['from_date'], $filters['to_date']]);
            }

            // Order by published date (descending by default)
            $query->orderBy('created_at', 'desc');

            // Paginate results
            $perPage = min($filters['limit'] ?? 10, 20); // Ensure maximum limit is 20
            $currentPage = $filters['page'] ?? 1; // Default to page 1 if not provided

            return $query->paginate($perPage, ['*'], 'page', $currentPage);
        } catch (\Throwable $e) {
            Log::error('Error in ArticleService::search: '.$e->getMessage());
            throw new \Exception('Failed to fetch articles.');
        }
    }

    /**
     * Fetch article
     */
    public function show($id): Article
    {
        try {
            return Article::query()->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new \Exception('No record found.', 404);
        } catch (\Throwable $e) {
            Log::error('Error in ArticleService::show: '.$e->getMessage());
            throw new \Exception('Failed to fetch article.');
        }
    }
}
