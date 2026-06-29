{{-- 💡 事先在頂端定義好 class 與屬性的 Blade 變數，下方乾淨又好維護 --}}
@php
    $isReadonly = (isset($readonly) && $readonly == 1) ? 'readonly' : '';
    $isDisabled = (isset($readonly) && $readonly == 1) ? 'disabled' : ''; // 💡 Select 唯讀需要用 disabled
    $inputBg = (isset($readonly) && $readonly == 1) ? 'bg-light' : '';
@endphp

{{-- 🔥 新增專屬列印的樣式優化，解決擠壓問題 --}}
<style type="text/css">
    @media print {
        /* 讓表格在列印時強制使用 100% 寬度 */
        .print-full-width {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        /* 列印時移除 table-responsive 的捲軸限制，讓表格自然展開 */
        .table-responsive {
            overflow-x: visible !important;
            display: block !important;
        }
        /* 縮小一點點表格字體與輸入框內距，確保 11 個欄位能完美塞進 A4 橫/直向 */
        table.table th, table.table td {
            font-size: 13px !important;
            padding: 4px 2px !important;
        }
        .input-group-text, input.form-control, select.form-select {
            font-size: 13px !important;
            padding: 2px !important;
        }
    }
</style>

{{-- 💡 將原來的 container 改為 print-full-width，並在非列印時使用 my-4 保持與畫面的間距 --}}
<div class="print-full-width my-4">
    
    <div class="card mb-4">
        <div class="card-header bg-light fw-bold">學生上學交通方式調查</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="min-width: 65px;">項目</th>
                            <th scope="col">步行</th>
                            <th scope="col">騎自行車</th>
                            <th scope="col">騎電動輔助自行車</th>
                            <th scope="col">騎微型電動二輪車</th>
                            <th scope="col">騎機車<br><small class="text-muted">(滿18歲)</small></th>
                            <th scope="col">搭公車<br><span class="text-danger small">(含幸福巴士)</span></th>
                            <th scope="col">搭學校專車</th>
                            <th scope="col">家長接送<br><small class="text-muted">(機車)</small></th>
                            <th scope="col">家長接送<br><small class="text-muted">(汽車)</small></th>
                            <th scope="col" style="min-width: 80px;">合計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" class="table-light">人數</th>
                            <td><input type="number" name="go_walk_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_walk_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_bike_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_bike_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_ebike_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_ebike_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_escooter_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_escooter_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_motor_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_motor_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_bus_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_bus_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_school_bus_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_school_bus_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_parent_motor_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_parent_motor_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="go_parent_car_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['go_parent_car_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td class="table-light fw-bold">
                                <input type="number" name="go_total_count" class="form-control form-control-sm text-center fw-bold bg-light" readonly placeholder="0">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="table-light">比率</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_walk_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_bike_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_ebike_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_escooter_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_motor_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_bus_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_school_bus_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_parent_motor_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_parent_car_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td class="table-light fw-bold">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="go_total_rate" class="form-control text-center fw-bold bg-light" readonly>
                                    <span class="input-group-text px-1 fw-bold">%</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>                            
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-light fw-bold">學生放學交通方式調查</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="min-width: 65px;">項目</th>
                            <th scope="col">步行</th>
                            <th scope="col">騎自行車</th>
                            <th scope="col">騎電動輔助自行車</th>
                            <th scope="col">騎微型電動二輪車</th>
                            <th scope="col">騎機車<br><small class="text-muted">(滿18歲)</small></th>
                            <th scope="col">搭公車<br><span class="text-danger small">(含幸福巴士)</span></th>
                            <th scope="col">搭學校專車</th>
                            <th scope="col">家長接送<br><small class="text-muted">(機車)</small></th>
                            <th scope="col">家長接送<br><small class="text-muted">(汽車)</small></th>
                            <th scope="col">家長接送<br><small class="text-muted">(安親班)</small></th>
                            <th scope="col" style="min-width: 80px;">合計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row" class="table-light">人數</th>
                            <td><input type="number" name="back_walk_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_walk_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_bike_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_bike_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_ebike_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_ebike_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_escooter_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_escooter_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_motor_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_motor_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_bus_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_bus_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_school_bus_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_school_bus_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_parent_motor_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_parent_motor_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_parent_car_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_parent_car_count'] ?? 0 }}" {{ $isReadonly }}></td>
                            <td><input type="number" name="back_care_count" class="form-control form-control-sm text-center {{ $inputBg }}" value="{{ $answer_data['back_care_count'] ?? 0 }}" {{ $isReadonly }}></td>                                        
                            <td class="table-light fw-bold">
                                <input type="number" name="back_total_count" class="form-control form-control-sm text-center fw-bold bg-light" readonly placeholder="0">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" class="table-light">比率</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_walk_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_bike_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_ebike_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_escooter_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_motor_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_bus_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_school_bus_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_parent_motor_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_parent_car_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_care_rate" class="form-control text-center bg-light" readonly>
                                    <span class="input-group-text px-1">%</span>
                                </div>
                            </td>
                            <td class="table-light fw-bold">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="back_total_rate" class="form-control text-center fw-bold bg-light" readonly>
                                    <span class="input-group-text px-1 fw-bold">%</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>                            
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light fw-bold">
                    交通安全教育輔導與管理 —— 是否提供學生車輛停放空間 <span class="text-danger small">(1車1格)</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered align-middle text-center mb-0" style="height: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">自行車格數</th>
                                <th scope="col">電動輔助自行車格數</th>
                                <th scope="col">微型電動二輪車格數</th>
                                <th scope="col">機車格數</th>
                                <th scope="col">合計</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="number" name="park_bike" class="form-control text-center {{ $inputBg }}" value="{{ $answer_data['park_bike'] ?? 0 }}" {{ $isReadonly }}></td>
                                <td><input type="number" name="park_ebike" class="form-control text-center {{ $inputBg }}" value="{{ $answer_data['park_ebike'] ?? 0 }}" {{ $isReadonly }}></td>
                                <td><input type="number" name="park_escooter" class="form-control text-center {{ $inputBg }}" value="{{ $answer_data['park_escooter'] ?? 0 }}" {{ $isReadonly }}></td>
                                <td><input type="number" name="park_motor" class="form-control text-center {{ $inputBg }}" value="{{ $answer_data['park_motor'] ?? 0 }}" {{ $isReadonly }}></td>                                            
                                <td class="table-light">
                                    <input type="number" name="park_total" class="form-control text-center fw-bold bg-light" readonly placeholder="0">
                                </td>
                            </tr>
                        </tbody>
                    </table>                                
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-light fw-bold">
                    是否針對騎車學生進行考照輔導
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered align-middle text-center mb-0" style="height: 100%;">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">自行車考照</th>
                                <th scope="col">電動輔助自行車考照</th>
                                <th scope="col">微型電動二輪車考照</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select name="guide_bike" class="form-select form-select-sm text-center {{ $inputBg }}" {{ $isDisabled }}>
                                        <option value="">-- 請選擇 --</option>
                                        <option value="是" {{ (isset($answer_data['guide_bike']) && $answer_data['guide_bike'] === '是') ? 'selected' : '' }}>是</option>
                                        <option value="否" {{ (isset($answer_data['guide_bike']) && $answer_data['guide_bike'] === '否') ? 'selected' : '' }}>否</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="guide_ebike" class="form-select form-select-sm text-center {{ $inputBg }}" {{ $isDisabled }}>
                                        <option value="">-- 請選擇 --</option>
                                        <option value="是" {{ (isset($answer_data['guide_ebike']) && $answer_data['guide_ebike'] === '是') ? 'selected' : '' }}>是</option>
                                        <option value="否" {{ (isset($answer_data['guide_ebike']) && $answer_data['guide_ebike'] === '否') ? 'selected' : '' }}>否</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="guide_escooter" class="form-select form-select-sm text-center {{ $inputBg }}" {{ $isDisabled }}>
                                        <option value="">-- 請選擇 --</option>
                                        <option value="是" {{ (isset($answer_data['guide_escooter']) && $answer_data['guide_escooter'] === '是') ? 'selected' : '' }}>是</option>
                                        <option value="否" {{ (isset($answer_data['guide_escooter']) && $answer_data['guide_escooter'] === '否') ? 'selected' : '' }}>否</option>
                                    </select>
                                </td>                                            
                            </tr>
                        </tbody>
                    </table>                                
                </div>
            </div>
        </div>
    </div>

</div>

<script>
$(document).ready(function() {
    // ==========================================
    // 1. 學生上學交通方式計算
    // ==========================================
    $('input[name^="go_"][name$="_count"]').not('[name="go_total_count"]').on('input', function() {
        let total = 0;
        let counts = {};

        $('input[name^="go_"][name$="_count"]').not('[name="go_total_count"]').each(function() {
            let val = parseInt($(this).val()) || 0;
            if (val < 0) { val = 0; $(this).val(0); }
            let name = $(this).attr('name');
            counts[name] = val;
            total += val;
        });

        $('input[name="go_total_count"]').val(total);

        $('input[name^="go_"][name$="_count"]').not('[name="go_total_count"]').each(function() {
            let name = $(this).attr('name');
            let rateName = name.replace('_count', '_rate');
            let inputRate = $(`input[name="${rateName}"]`);

            if (total > 0) {
                let rate = ((counts[name] / total) * 100).toFixed(1);
                inputRate.val(rate); 
            } else {
                inputRate.val('');
            }
        });

        if (total > 0) {
            $('input[name="go_total_rate"]').val('100.0');
        } else {
            $('input[name="go_total_rate"]').val('');
        }
    });

    // ==========================================
    // 2. 學生放學交通方式計算
    // ==========================================
    $('input[name^="back_"][name$="_count"]').not('[name="back_total_count"]').on('input', function() {
        let total = 0;
        let counts = {};

        $('input[name^="back_"][name$="_count"]').not('[name="back_total_count"]').each(function() {
            let val = parseInt($(this).val()) || 0;
            if (val < 0) { val = 0; $(this).val(0); }
            let name = $(this).attr('name');
            counts[name] = val;
            total += val;
        });

        $('input[name="back_total_count"]').val(total);

        $('input[name^="back_"][name$="_count"]').not('[name="back_total_count"]').each(function() {
            let name = $(this).attr('name');
            let rateName = name.replace('_count', '_rate');
            let inputRate = $(`input[name="${rateName}"]`);

            if (total > 0) {
                let rate = ((counts[name] / total) * 100).toFixed(1);
                inputRate.val(rate);
            } else {
                inputRate.val('');
            }
        });

        if (total > 0) {
            $('input[name="back_total_rate"]').val('100.0');
        } else {
            $('input[name="back_total_rate"]').val('');
        }
    });

    // ==========================================
    // 3. 學生車輛停放空間計算
    // ==========================================
    $('input[name^="park_"]').not('[name="park_total"]').on('input', function() {
        let total = 0;
        $('input[name^="park_"]').not('[name="park_total"]').each(function() {
            let val = parseInt($(this).val()) || 0;
            if (val < 0) { val = 0; $(this).val(0); }
            total += val;
        });
        $('input[name="park_total"]').val(total);
    });

    // ==========================================
    // 🔥 關鍵改動：已將第 4 區塊的 JS 監聽與初始化完全刪除
    // ==========================================

    // 網頁一載入完成，立刻自動觸發前三個區塊的計算
    $('input[name="go_walk_count"]').trigger('input');
    $('input[name="back_walk_count"]').trigger('input');
    $('input[name="park_bike"]').trigger('input');
});
</script>