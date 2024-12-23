<?php

namespace App\Http\Controllers\Api\V1\Article;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Resources\ArticleResource;
use App\Services\ArticleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShowArticleController extends BaseApiController
{
    public function __invoke($id, Request $request, ArticleService $articleService): JsonResponse
    {
        try {
            $article = $articleService->show($id);

            return $this->jsonSuccessWithData([
                'articles' => new ArticleResource($article),
            ], 'Article fetched');
        } catch (\Exception $exception) {
            $statusCode = $exception->getCode() === 404 ? 404 : 500;
            $errorMessage = $statusCode === 404 ? $exception->getMessage() : 'An unexpected error occurred. Please try again later.';

            Log::error('Error in ShowArticleController: '.$exception->getMessage());

            return $this->jsonError($errorMessage, $statusCode);
        }
    }
}
