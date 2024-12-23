<?php

use App\Console\Commands\ManageArticlesCommand;
use Illuminate\Support\Facades\Schedule;

Schedule::command(ManageArticlesCommand::class)->everyTwoHours();
