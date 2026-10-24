<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class OnlineTrackerService
{
    /**
     * 常見爬蟲 / 機器人關鍵字 (過濾非真實訪客)
     */
    protected array $botKeywords = [
        'bot', 'spider', 'crawler', 'slurp', 'curl', 'guzzle', 
        'python', 'headless', 'facebookexternalhit', 'preview', 'meta-externalagent'
    ];

    /**
     * 取得線上人數統計 (含線上會員詳細名單)
     * 
     * @param int $minutes 判定在線的時間區間（預設 5 分鐘）
     * @param bool $useCache 是否啟用短暫快取（建議開啟）
     */
    public function getStats(int $minutes = 5, bool $useCache = true): array
    {
        $cacheKey = "online_users_stats_{$minutes}min";

        if (!$useCache) {
            return $this->calculate($minutes);
        }

        // 短暫快取 15 秒，兼顧準確度與 DB 效能
        return Cache::remember($cacheKey, now()->addSeconds(15), function () use ($minutes) {
            return $this->calculate($minutes);
        });
    }

    /**
     * 執行精準 SQL 查詢並載入會員資料
     */
    private function calculate(int $minutes): array
    {
        $threshold = now()->subMinutes($minutes)->timestamp;

        // 1. 建立基礎 Query (限定時間與排除爬蟲)
        $baseQuery = DB::table('sessions')
            ->where('last_activity', '>=', $threshold)
            ->where(function ($query) {
                foreach ($this->botKeywords as $keyword) {
                    $query->where('user_agent', 'NOT LIKE', "%{$keyword}%");
                }
            });

        // 2. 取得線上已登入會員詳細清單
        $onlineUsers = $this->getOnlineUsers($minutes);
        $loggedInUsersCount = $onlineUsers->count();

        // 3. 計算「線上訪客」 (user_id 為 null 的 Session 筆數)
        $guestUsersCount = (clone $baseQuery)
            ->whereNull('user_id')
            ->count('id');

        return [
            'total'        => $loggedInUsersCount + $guestUsersCount, // 總線上人數
            'logged_in'    => $loggedInUsersCount,                   // 已登入會員數
            'guest'        => $guestUsersCount,                      // 訪客數
            'online_users' => $onlineUsers,                           // 線上會員詳細清單
        ];
    }

    /**
     * 清除指定時間區間的統計快取
     */
    public function clearCache(int $minutes = 5): void
    {
        Cache::forget("online_users_stats_{$minutes}min");
    }

    /**
     * 取得當前線上的會員詳細資料清單
     * 
     * @param int $minutes 判定區間（預設 5 分鐘）
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOnlineUsers(int $minutes = 5)
    {
        $threshold = now()->subMinutes($minutes)->timestamp;

        // 1. 取得指定時間內有活動且非爬蟲的 user_id 清單
        $userIds = DB::table('sessions')
            ->where('last_activity', '>=', $threshold)
            ->whereNotNull('user_id')
            ->where(function ($query) {
                foreach ($this->botKeywords as $keyword) {
                    $query->where('user_agent', 'NOT LIKE', "%{$keyword}%");
                }
            })
            ->pluck('user_id')
            ->unique();

        // 2. 查詢並回傳 User Model（已加入 school 與 title 欄位）
        return User::whereIn('id', $userIds)
            ->select(['id', 'name', 'username', 'email', 'school', 'title'])
            ->get();
    }
}