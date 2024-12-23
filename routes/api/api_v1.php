<?php

use App\Http\Controllers\Api\V1\Article\SearchArticleController;
use App\Http\Controllers\Api\V1\Article\ShowArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/articles', SearchArticleController::class);
Route::get('/article/{id}', ShowArticleController::class);
