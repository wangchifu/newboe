<?php

namespace App\Console\Commands;

use App\Services\SchoolStats;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Throwable;

class FetchSchoolStats extends Command
{
    protected $signature = 'school-stats:fetch';

    protected $description = '下載校務系統開放資料的學生數、班級數、教職員 xlsx（學校基本資料頁使用）';

    public function handle(): int
    {
        $failed = false;
        foreach (array_keys(SchoolStats::SOURCES) as $key) {
            try {
                $count = SchoolStats::fetch($key);
                $this->info("{$key}: {$count} 所學校");
            } catch (Throwable $e) {
                $failed = true;
                $this->error("{$key}: {$e->getMessage()}");
                Log::warning("school-stats:fetch {$key}: {$e->getMessage()}");
            }
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
