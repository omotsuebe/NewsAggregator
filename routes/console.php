<?php

use App\Console\Commands\ManageArticlesCommand;
use Illuminate\Support\Facades\Schedule;

// Run command (php artisan articles:manage)
Schedule::command(ManageArticlesCommand::class)->everyTwoHours();
