<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('about:kiwi', function () {
    $this->info('KIWI GROUP Humble Graduation Trip');
})->purpose('Show KIWI project information');
