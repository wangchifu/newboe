<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\OnlineTrackerService;

class OnlineStats extends Component
{
    public array $stats;

    public function __construct(OnlineTrackerService $onlineTracker)
    {
        // 直接注入 Service 取得統計數據
        $this->stats = $onlineTracker->getStats();
    }

    public function render(): View|Closure|string
    {
        return view('components.online-stats');
    }
}