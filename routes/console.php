<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// 學生基本資料：每小時下載校務系統開放資料 xlsx（正式機需有 cron 執行 php artisan schedule:run）
Schedule::command('school-stats:fetch')->hourly()->withoutOverlapping();
