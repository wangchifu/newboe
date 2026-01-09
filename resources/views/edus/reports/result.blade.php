@extends('layouts.app')

@section('title','填報結果')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<style>
/* 1. 表格基礎設定 */
.table.table-bordered {
    border-collapse: separate !important;
    border-spacing: 0;
    border: none !important; /* 關閉原本的 table 邊框，改由內部儲存格控制 */
}

/* 2. 針對 thead th 的設定（固定表頭 + 粗底線 + 左右邊框） */
.table-bordered thead th {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #fff;
    
    /* 移除所有原生邊框，避免 sticky 造成的渲染異常 */
    border: none !important;
    
    /* 利用 box-shadow 畫出所有方向的線 */
    /* 參數：全框細線 (0.5px) + 底部粗線 (3px) */
    box-shadow: 
        inset 1px 0 0 0 #dee2e6,    /* 左邊框 */
        inset -1px 0 0 0 #dee2e6,   /* 右邊框 */
        inset 0 1px 0 0 #dee2e6,    /* 上邊框 */
        inset 0 -3px 0 0 #000000;   /* 下邊框：粗黑線 */
}

/* 3. 針對 tbody td 的設定（確保資料列邊框完整） */
.table-bordered tbody td {
    border: none !important;
    box-shadow: 
        inset 1px 0 0 0 #dee2e6,    /* 左邊框 */
        inset -1px 0 0 0 #dee2e6,   /* 右邊框 */
        inset 0 -1px 0 0 #dee2e6;   /* 下邊框 */
}
</style>
<div class="col-lg-12 mx-auto">
    <h1>{{ $report->name }}</h1>
    <a href="#" class="btn btn-secondary btn-sm" onclick="history.back()">返回</a>
    <a href="{{ route('edu_report.export',$report->id) }}" class="btn btn-primary btn-sm">下載 CSV 檔</a>
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
            <td style="box-shadow: inset 0 0 0 0.5px #dee2e6;border: none !important;">
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
                <td style="box-shadow: inset 0 0 0 0.5px #dee2e6;border: none !important;">
                    <?php
                        $school_code = $school->code_no;
                        //信義
                        if($school_code=="074541074774"){
                            $school_code="074541";
                            if(!isset($answer_data[$school_code][$question->id])){
                                $school_code="074774";
                            }
                            
                        };
                        //原斗
                        if($school_code=="074537074745"){
                            $school_code="074537";
                            if(!isset($answer_data[$school_code][$question->id])){
                                $school_code="074745";
                            }
                        };
                        //鹿江
                        if($school_code=="074542074778"){
                            $school_code="074542";
                            if(!isset($answer_data[$school_code][$question->id])){
                                $school_code="074778";
                            }
                        }
                        //民權074543074760
                        if($school_code=="074543074760"){
                            $school_code="074760";
                            if(!isset($answer_data[$school_code][$question->id])){
                                $school_code="074543";
                            }
                        }                        
                    ?>
                    @if(isset($answer_data[$school_code][$question->id]) and $report_school->situation==3)                                
                    {{ $answer_data[$school_code][$question->id] }}
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
@endsection