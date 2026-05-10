@extends('layouts.app')

@section('title','填報結果')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>{{ $report->name }}</h1>
    <a href="#" class="btn btn-secondary btn-sm" onclick="history.back()">返回</a>
    <a href="{{ route('edu_report.export',$report->id) }}" class="btn btn-primary btn-sm">下載 CSV 檔</a>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" style="table-layout: fixed; width:100%;">
            <thead>
                <tr>
                    <th>
                        學校
                    </th>
                    @foreach($report->questions as $question)
                    <th>
                        {{ $question->title }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            <?php $no_report_name=""; ?>
            @foreach($schools as $school)
            <tr>
                <td>
                    <?php
                    $report_school = \App\Models\ReportSchool::where('code',$school->code_no)
                        ->where('report_id',$report->id)
                        ->first();
                    ?>
                    @if($report_school->situation==3)
                        <span data-toggle="tooltip" data-placement="top" title="{{ $report_school->updated_at }} 送出">{{ $school->school_name }}</span>
                            <small class="text-secondary">填：{{ $report_school->signed_user->name }}</small><br>
                        <a href="#!" class="badge bg-danger" onclick="sw_confirm1('確定退回 {{ $school->school_name }}？','{{ route('edu_report.set_back',$report_school->id) }}')">退回</a>
                    @elseif($report_school->situation==4)
                        <span data-toggle="tooltip" data-placement="top" title="{{ $report_school->signed_at }} 送出">{{ $school->school_name }}</span><br>
                            <small class="text-secondary">填：{{ $report_school->signed_user->name }}</small><br>
                            <span class="text-danger">不填報</span>
                        <a href="#!" class="badge bg-danger" onclick="sw_confirm1('確定退回 {{ $school->school_name }}？','{{ route('edu_report.set_null',$report_school->id) }}')">退回</a>
                    @else
                        {{ $school->school_name }}
                    @endif
                </td>
                <?php $no_report=""; ?>
                @foreach($report->questions as $question)
                    <td data-bs-toggle="tooltip" data-bs-placement="top" title="{{  $question->title }}">
                        <?php
                        $school_code = $school->code_no;
                                                    
                        // 1. 定義學校群組對照表（把相關的代碼放在同一個群組內）
                        $school_groups = [
                            ['074541', '074541074774', '074774', '074774074541'], // 信義組
                            ['074537', '074537074745', '074745', '074745074537'], // 原斗組
                            ['074542', '074542074778', '074778', '074778074542'], // 鹿江組
                            ['074543', '074543074760', '074760', '074760074543'], // 民權組
                        ];

                        // 2. 只有在當前 $school_code 找不到資料時才執行
                        if (!isset($answer_data[$school_code][$question->id])) {
                            
                            foreach ($school_groups as $group) {
                                // 判斷目前的 $school_code 是否屬於這一個學校群組
                                if (in_array($school_code, $group)) {
                                    
                                    // 如果屬於這組，則在組內依序尋找有資料的代碼
                                    foreach ($group as $fallback_code) {
                                        if (isset($answer_data[$fallback_code][$question->id])) {
                                            $school_code = $fallback_code;
                                            break 2; // 跳出兩層迴圈 (找到了就直接結束所有檢查)
                                        }
                                    }
                                }
                            }
                        }                        
                                          
                        ?>
                        @if(isset($answer_data[$school_code][$question->id]) and $report_school->situation==3) 
                            <div class="d-block w-100 h-100 text-break" style="padding: 0px;">                               
                                {{ $answer_data[$school_code][$question->id] }}
                            </div>
                        @else
                            @if($report_school->situation != 4)
                                <?php $no_report=$school->school_name; ?>
                            @endif
                        @endif
                        
                    </td>
                @endforeach
                <?php
                    if($no_report){
                        $no_report_name .= $no_report.",";
                    }
                ?>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <hr>
    未填報學校：<br>
    <span class="text-danger">{{ $no_report_name }}</span>
    @if($no_report_name)
        <br>
        <form action="{{ route('edu_report.post') }}" method="post" id="report_post_form" onsubmit="return false">
            <input type="hidden" name="schools" value="{{ substr($no_report_name,0,-1) }}">
            <input type="hidden" name="report_id" value="{{ $report->id }}">
            @csrf
        <button class="btn btn-warning btn-sm" onclick="sw_confirm2('確定發簽收公告？','report_post_form')">發催促公告</button>
        </form>
    @endif     
    <br>
    <br>
</div>
<script>
  // 抓取頁面上所有帶有 data-bs-toggle="tooltip" 的元素並初始化
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })
</script>
@endsection