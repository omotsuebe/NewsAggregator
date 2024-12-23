<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ArticleSearchRequest;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class SearchArticleController extends BaseApiController
{
    public function __invoke(ArticleSearchRequest $request, ArticleService $articleService): JsonResponse
    {
        $filters = $request->validated();

        try {
            $articles = $articleService->search($filters);

            return $this->jsonSuccessWithData([
                'articles' => ArticleResource::collection($articles->items()),
                'meta' => [
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'last_page' => $articles->lastPage(),
                ],
            ], 'Articles fetched');
        } catch (\Exception $exception) {
            Log::error('Error in SearchArticleController: '.$exception->getMessage());

            return $this->jsonError('An unexpected error occurred. Please try again later.');
        }
    }
}
