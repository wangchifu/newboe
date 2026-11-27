<?php

namespace App\Http\Controllers;

use App\Services\SchoolStats;
use Illuminate\Http\Request;
use Rap2hpoutre\FastExcel\FastExcel;

/**
 * 學生基本資料（公開頁，放在「學校之美」底下）。
 * 資料來自校務系統開放資料的 xlsx，見 App\Services\SchoolStats。
 */
class SchoolStatController extends Controller
{
    private const GRADES = [
        '國民小學' => ['一年級', '二年級', '三年級', '四年級', '五年級', '六年級'],
        '國民中學' => ['七年級', '八年級', '九年級'],
    ];

    private const SPECIAL_ED = '特教班';

    private const KIND_ORDER = ['普通班', '體育班', '藝才班', '才藝班', '實驗班', '特教班'];

    public function index()
    {
        return view('school_stats.index');
    }

    /** 各分頁的資料（JSON），學校分頁讀學校清單，其他讀下載的 xlsx */
    public function data(string $key)
    {
        if ($key === 'schools') {
            $rows = array_map(fn ($s) => $s + [
                'intro' => route('introductions.school_show', $s['code']),
            ], SchoolStats::schools());

            return response()->json(['updated_at' => null, 'rows' => $rows]);
        }

        return response()->json(SchoolStats::get($key) ?? ['updated_at' => null, 'rows' => []]);
    }

    /** 匯出 Excel：篩選條件與頁面相同（鄉鎮市、學制、校名、縣立／非縣立），欄位同 Go 版 */
    public function export(Request $request, string $key)
    {
        $data = SchoolStats::get($key);
        abort_if($data === null, 404, '尚未下載資料');

        $district = (string) $request->query('district', 'all');
        $type = (string) $request->query('type', 'all');
        $q = trim((string) $request->query('q', ''));
        $county = $request->query('county', '1') === '1';
        $nonCounty = $request->query('noncounty', '1') === '1';

        $rows = array_values(array_filter($data['rows'], function ($s) use ($district, $type, $q, $county, $nonCounty) {
            if ($district !== 'all' && $s['district'] !== $district) {
                return false;
            }
            if ($type !== 'all' && $s['type'] !== $type) {
                return false;
            }
            $isCounty = str_starts_with($s['code'], '074');
            if ($isCounty ? ! $county : ! $nonCounty) {
                return false;
            }

            return $q === '' || str_contains($s['name'], $q);
        }));
        abort_if($rows === [], 404, '沒有符合條件的學校');

        if ($key === 'students') {
            $grades = [...(self::GRADES[$type] ?? array_merge(...array_values(self::GRADES))), self::SPECIAL_ED];
            $list = array_map(function ($s) use ($grades) {
                $out = [
                    '行政區' => $s['district'],
                    '教育部代碼' => $s['code'],
                    '學校名稱' => $s['name'],
                    '學校學制' => $s['type'],
                    '男學生數' => $s['boy'],
                    '女學生數' => $s['girl'],
                    '總學生數' => $s['total'],
                ];
                foreach ($grades as $g) {
                    $d = ((array) $s['details'])[$g] ?? ['c' => 0, 'b' => 0, 'g' => 0];
                    $label = $g === self::SPECIAL_ED ? '特教班' : $this->gradeLabel($g);
                    $out["{$label}男生數"] = $d['b'];
                    $out["{$label}女生數"] = $d['g'];
                    $out[$g === self::SPECIAL_ED ? '特教班級數' : "{$label}班級數"] = $d['c'];
                }

                return $out;
            }, $rows);
            $name = '學生數統計';
        } else {
            $kinds = $this->kinds($rows);
            $list = array_map(function ($s) use ($kinds) {
                $out = ['學校代碼' => $s['code'], '學校名稱' => $s['name']];
                $sum = 0;
                foreach ($kinds as $k) {
                    $d = ((array) $s['details'])[$k] ?? ['c' => 0, 'b' => 0, 'g' => 0, 't' => 0];
                    $out["{$k}-班數"] = $d['c'];
                    $out["{$k}-男"] = $d['b'];
                    $out["{$k}-女"] = $d['g'];
                    $out["{$k}-計"] = $d['t'];
                    $sum += $d['c'];
                }
                $out['班級數合計'] = $sum;

                return $out;
            }, $rows);
            $name = '班級數統計';
        }

        // FastExcel 依副檔名決定格式，所以檔名要以 .xlsx 結尾；送出後刪除
        $tmp = sys_get_temp_dir().'/school_stats_'.bin2hex(random_bytes(8)).'.xlsx';
        (new FastExcel(collect($list)))->export($tmp);

        return response()->download($tmp)
            ->setContentDisposition('attachment', "{$name}.xlsx", "school_stats_{$key}.xlsx")
            ->deleteFileAfterSend();
    }

    /** 一年級 → 1年級（同 Go 版匯出的欄名） */
    private function gradeLabel(string $g): string
    {
        $n = array_search(mb_substr($g, 0, 1), ['一', '二', '三', '四', '五', '六', '七', '八', '九'], true);

        return $n === false ? $g : ($n + 1).'年級';
    }

    /** 這些學校出現過的班別，依固定順序，其餘依名稱 */
    private function kinds(array $rows): array
    {
        $seen = [];
        foreach ($rows as $s) {
            foreach (array_keys((array) $s['details']) as $k) {
                $seen[$k] = true;
            }
        }
        $kinds = array_keys($seen);
        // 其餘班別依中文排序，與頁面（localeCompare zh-TW）一致
        $collator = class_exists(\Collator::class) ? new \Collator('zh_TW') : null;
        usort($kinds, function ($a, $b) use ($collator) {
            $ia = array_search($a, self::KIND_ORDER, true);
            $ib = array_search($b, self::KIND_ORDER, true);
            if ($ia === false && $ib === false) {
                return $collator ? $collator->compare($a, $b) : strcmp($a, $b);
            }
            if ($ia === false) {
                return 1;
            }
            if ($ib === false) {
                return -1;
            }

            return $ia <=> $ib;
        });

        return $kinds;
    }
}
