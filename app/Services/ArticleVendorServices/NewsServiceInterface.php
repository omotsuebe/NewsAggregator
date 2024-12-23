<?php

namespace App\Services\ArticleVendorServices;

interface NewsServiceInterface
{
    public function fetchArticles(array $queryParams = []): array;

    public function saveArticles(): void;
}
