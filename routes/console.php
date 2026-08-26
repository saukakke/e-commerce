<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('shoply:status', function () {
    $this->info('Shoply e-commerce MVP is ready.');
})->purpose('Check Shoply application status');
