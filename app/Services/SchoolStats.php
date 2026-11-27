<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use OpenSpout\Reader\XLSX\Reader;
use RuntimeException;
use Throwable;

use function Illuminate\Support\defer;

/**
 * 學生基本資料：讀校務系統開放資料的 xlsx。
 *
 * 每小時由 school-stats:fetch 下載到 storage/app/school_stats/，頁面只讀這些檔案。
 * 下載失敗或檔案不對時保留上一份，不會蓋掉。
 */
class SchoolStats
{
    /** 資料代號 => 開放資料 API 名稱 */
    public const SOURCES = [
        'students' => 'std-grades',      // 各年級學生數
        'classes' => 'std-class-kinds',  // 各班別班級數
        'staff' => 'teacher-number',     // 教職員
    ];

    public const URL = 'https://cloudschool.chc.edu.tw/opendata/api/%s/type/xlsx';

    /** 改了解析或篩選規則就加 1，讓快取重算 */
    private const CACHE_VERSION = 1;

    /** 學校數遠低於此值就當作下載到的檔案有問題（目前約 226 所） */
    private const MIN_ROWS = 100;

    /** 前 7 欄：行政區、教育部代碼、學校名稱、學校學制、男、女、總數 */
    private const TOTAL_COLUMNS = [
        'students' => ['男學生數', '女學生數', '總學生數'],
        'classes' => ['男學生數', '女學生數', '總學生數'],
        'staff' => ['教職員男生數', '教職員女生數', '教職員總人數'],
    ];

    public static function path(string $key): string
    {
        return storage_path("app/school_stats/{$key}.xlsx");
    }

    /**
     * 下載一份 xlsx，確認讀得懂才換掉舊檔。回傳學校數，失敗丟例外。
     */
    public static function fetch(string $key, int $timeout = 300): int
    {
        $url = sprintf(self::URL, self::SOURCES[$key]);
        $response = Http::connectTimeout(min(30, $timeout))->timeout($timeout)->get($url);
        if (! $response->successful()) {
            throw new RuntimeException("下載失敗 HTTP {$response->status()}：{$url}");
        }
        $body = $response->body();
        if (! str_starts_with($body, 'PK')) {
            throw new RuntimeException("下載到的不是 xlsx：{$url}");
        }

        $path = self::path($key);
        File::ensureDirectoryExists(dirname($path));
        // 排程和首次開頁可能同時下載：各用各的暫存檔，最後 rename 是原子動作
        $tmp = $path.'.'.bin2hex(random_bytes(6)).'.tmp';
        file_put_contents($tmp, $body);
        try {
            $count = count(self::parse($key, $tmp));
            if ($count < self::MIN_ROWS) {
                throw new RuntimeException("只有 {$count} 所學校，不採用：{$url}");
            }
        } catch (Throwable $e) {
            @unlink($tmp);
            throw $e;
        }
        rename($tmp, $path);

        return $count;
    }

    /**
     * 讀已下載的資料，依檔案時間快取。還沒下載過回傳 null。
     *
     * @return array{updated_at: string, rows: array}|null
     */
    public static function get(string $key): ?array
    {
        $path = self::path($key);
        clearstatcache(true, $path);
        if (! is_file($path)) {
            self::fetchMissingLater();

            return null;
        }
        $mtime = filemtime($path);
        // 檔案、學校清單或篩選規則（CACHE_VERSION）有變就重新解析
        $version = self::CACHE_VERSION.':'.$mtime.':'.filemtime(resource_path('data/school_info.csv'));
        $cacheKey = "school_stats:{$key}";
        $cached = Cache::get($cacheKey);
        if (! is_array($cached) || ($cached['version'] ?? null) !== $version) {
            $cached = [
                'version' => $version,
                'rows' => self::sortBySchoolList(self::parse($key, $path)),
            ];
            Cache::forever($cacheKey, $cached);
        }

        return [
            'updated_at' => date('Y/m/d H:i', $mtime),
            'rows' => $cached['rows'],
        ];
    }

    /**
     * 還沒有檔案（剛部署、排程還沒跑）時，回應送出後才下載缺的檔（defer），
     * 網頁請求不用等下載；頁面會顯示「資料下載中」並自動重試。
     * 10 分鐘內只試一次：成功就清掉標記，失敗就等標記過期再試。
     */
    private static function fetchMissingLater(): void
    {
        if (! Cache::add('school_stats:fetching', true, 600)) {
            return; // 已在下載，或 10 分鐘內剛失敗過
        }
        defer(function () {
            $ok = true;
            foreach (array_keys(self::SOURCES) as $key) {
                clearstatcache(true, self::path($key));
                if (is_file(self::path($key))) {
                    continue;
                }
                try {
                    self::fetch($key, 120);
                } catch (Throwable $e) {
                    $ok = false;
                    Log::warning("school_stats 首次下載 {$key} 失敗：{$e->getMessage()}");
                }
            }
            if ($ok) {
                Cache::forget('school_stats:fetching');
            }
        });
    }

    /**
     * 把 xlsx 轉成每校一筆：
     * code, name, district, type, boy, girl, total, details。
     * 學生、班級的 details：{ 年級或班別: {c: 班級數, b: 男, g: 女, t: 計} }；
     * 教職員的 details：{ 職稱: 人數 }。空白或 0 的項目不放。
     */
    public static function parse(string $key, string $path): array
    {
        [$boyCol, $girlCol, $totalCol] = self::TOTAL_COLUMNS[$key];
        $reader = new Reader;
        $reader->open($path);
        $header = null;
        $rows = [];
        try {
            foreach ($reader->getSheetIterator() as $sheet) {
                foreach ($sheet->getRowIterator() as $row) {
                    $cells = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $row->toArray());
                    if ($header === null) {
                        $header = $cells;
                        foreach (['教育部代碼', '學校名稱', $totalCol] as $need) {
                            if (! in_array($need, $header, true)) {
                                throw new RuntimeException("xlsx 找不到「{$need}」欄");
                            }
                        }

                        continue;
                    }
                    $r = [];
                    foreach ($header as $i => $name) {
                        $r[$name] = $cells[$i] ?? '';
                    }
                    $code = self::code($r['教育部代碼']);
                    if ($code === '') {
                        continue;
                    }
                    $details = [];
                    foreach (array_slice($header, 7) as $name) {
                        $value = self::int($r[$name]);
                        if ($value === 0) {
                            continue;
                        }
                        if ($key === 'staff') {
                            $details[$name] = $value;
                        } elseif (preg_match('/^(.+?)(班級數|男生數|女生數|總學生數)$/u', $name, $m)) {
                            $field = ['班級數' => 'c', '男生數' => 'b', '女生數' => 'g', '總學生數' => 't'][$m[2]];
                            $details[$m[1]] ??= ['c' => 0, 'b' => 0, 'g' => 0, 't' => 0];
                            $details[$m[1]][$field] = $value;
                        }
                    }
                    $rows[] = [
                        'code' => $code,
                        'name' => (string) $r['學校名稱'],
                        'district' => (string) ($r['行政區'] ?? ''),
                        'type' => (string) ($r['學校學制'] ?? ''),
                        'boy' => self::int($r[$boyCol] ?? 0),
                        'girl' => self::int($r[$girlCol] ?? 0),
                        'total' => self::int($r[$totalCol]),
                        'details' => (object) $details,
                    ];
                }
                break; // 只有第一個工作表
            }
        } finally {
            $reader->close();
        }
        if ($header === null) {
            throw new RuntimeException('xlsx 是空的');
        }

        return $rows;
    }

    /**
     * 學校清單（resources/data/school_info.csv），順序同 Go 版：依鄉鎮市排列。
     *
     * @return array<int, array{code: string, name: string, type: string, district: string, address: string, phone: string, website: string}>
     */
    public static function schools(): array
    {
        static $schools = null;
        if ($schools !== null) {
            return $schools;
        }
        $schools = [];
        $file = fopen(resource_path('data/school_info.csv'), 'r');
        fgetcsv($file); // 標題列
        while (($line = fgetcsv($file)) !== false) {
            if (count($line) < 7 || $line[0] === '') {
                continue;
            }
            $schools[] = [
                'code' => $line[0],
                'name' => $line[1],
                'type' => $line[2],
                'district' => $line[3],
                'address' => $line[4],
                'phone' => $line[5],
                'website' => $line[6],
            ];
        }
        fclose($file);

        return $schools;
    }

    /** 不是學校的列（同 Go 版 pkg/cache/cache.go 的 excludedSchoolNames） */
    private const EXCLUDED_NAMES = ['教育處', '教網中心', '教育處測試國中', '教育處測試國小', '縣立民靖國小', '縣立永光國小', '縣立潭墘國小'];

    /**
     * 去掉不是學校的列（代碼不是 0 開頭、排除名單、「以下…」分隔列），
     * 再依學校清單的順序排（代碼對不到就用校名）；清單沒有的學校放最後，依代碼排。
     */
    private static function sortBySchoolList(array $rows): array
    {
        $rows = array_values(array_filter($rows, fn ($s) => str_starts_with($s['code'], '0')
            && ! in_array($s['name'], self::EXCLUDED_NAMES, true)
            && ! str_starts_with($s['name'], '以下')));

        $order = [];
        foreach (self::schools() as $i => $s) {
            $order['name:'.$s['name']] ??= $i;
            $order['code:'.$s['code']] = $i;
        }
        $pos = fn ($s) => $order['code:'.$s['code']] ?? $order['name:'.$s['name']] ?? PHP_INT_MAX;
        usort($rows, fn ($a, $b) => $pos($a) <=> $pos($b) ?: strcmp($a['code'], $b['code']));

        return $rows;
    }

    private static function code(mixed $v): string
    {
        // 代碼在 xlsx 裡是文字；萬一變成數字，補回開頭的 0
        if (is_int($v) || is_float($v)) {
            return str_pad((string) (int) $v, 6, '0', STR_PAD_LEFT);
        }

        return trim((string) $v);
    }

    private static function int(mixed $v): int
    {
        if (is_int($v)) {
            return $v;
        }
        if (is_float($v)) {
            return (int) round($v);
        }
        $v = str_replace(',', '', trim((string) $v));

        return is_numeric($v) ? (int) round((float) $v) : 0;
    }
}
