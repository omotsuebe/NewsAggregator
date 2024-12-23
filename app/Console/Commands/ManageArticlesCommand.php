<?php

namespace App\Console\Commands;

use App\Services\ArticleVendorServices\GuardianService;
use App\Services\ArticleVendorServices\NewsAPIService;
use App\Services\ArticleVendorServices\NewYorkTimesService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class ManageArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and save articles from NewsAPI, Guardian, and New York Times in sequence.';

    /**
     * Execute the console command.
     */
    public function handle(
        NewsAPIService $newsAPIService,
        GuardianService $guardianService,
        NewYorkTimesService $newYorkTimesService
    ): int {
        $this->info('Starting the article management process...');

        // NewsAPI Service
        try {
            $this->info('Fetching articles from NewsAPI...');
            $newsAPIService->saveArticles();
            $this->info('NewsAPI articles saved successfully.');
        } catch (\Exception $e) {
            $this->error('NewsAPI Service failed: '.$e->getMessage());
        }

        // Guardian Service
        try {
            $this->info('Fetching articles from The Guardian...');
            $guardianService->saveArticles();
            $this->info('The Guardian articles saved successfully.');
        } catch (\Exception $e) {
            $this->error('Guardian Service failed: '.$e->getMessage());
        }

        // New York Times Service
        try {
            $this->info('Fetching articles from The New York Times...');
            $newYorkTimesService->saveArticles();
            $this->info('The New York Times articles saved successfully.');
        } catch (\Exception $e) {
            $this->error('New York Times Service failed: '.$e->getMessage());
        }

        $this->info('Article management process completed.');

        return CommandAlias::SUCCESS;
    }
}
