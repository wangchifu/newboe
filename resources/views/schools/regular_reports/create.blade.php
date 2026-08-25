@extends('layouts.app_clean')

@section('title','新增定期填報內容')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card shadow-sm my-3">
        <div class="card-header">            
            <img class="card-img-top img-responsive" src="{{ asset('images/small/regular.jpeg') }}">
        </div>
        <div class="card-body">
            <span class="text-right">
                {{ $sections[$regular_report_school->regular_report->section_id] ?? '' }} /
                {{ $regular_report_school->regular_report->user->name }} / 
                @if(!empty($regular_report_school->regular_report->user->telephone)) / 
                    <i class="fas fa-phone"></i> {{ $regular_report_school->regular_report->user->telephone }} / 
                @endif                
                {{ $regular_report_school->regular_report->passed_at }} 發佈 /
                <span class="text-danger">{{ $regular_report_school->regular_report->start_date }} 開始</span> /                 
                <span class="text-danger">{{ $regular_report_school->regular_report->die_date }} 截止</span> /                 
            </span>
            <h4>
                @if( $regular_report_school->regular_report->situation !=4)
                    {{ $regular_report_school->regular_report->name }}
                @else
                    <span style="color:red">[填報作廢]</span>
                    <strike class="text-primary">
                        {{ $regular_report_school->regular_report->name }}
                    </strike></a> 
                @endif
            </h4>
            @if(!empty($regular_report_school->regular_report->regular_sample->content))
                <div class="form-group">
                    <strong>說明：</strong><br>
                    {!! $regular_report_school->regular_report->regular_sample->content !!}
                </div>
            @endif
            <?php
            $files = get_files(storage_path('app/public/regular_report_files/' . $regular_report_school->regular_report->id));
            ?>
            @if(!empty($files))
                <div class="form-group">
                    <strong>附檔：</strong><br>
                    @foreach($files as $k=>$v)
                        <a href="{{ route('edu_regular_report.download',['id'=>$regular_report_school->regular_report->id,'filename'=>$v]) }}" class="btn btn-primary btn-sm" style="margin:3px"><i class="fas fa-download"></i> {{ $v }}</a>
                    @endforeach
                </div>
            @endif
            <hr>
            <h4 class="text-danger">題目與填報</h4>
            @include('layouts.errors')
            <span class="text-danger">* 每題都是必填，若題目不合，請電洽縣府承辦人，或填「無、0」。</span><br>
            <span class="text-danger">* 往年若曾填過相同表單，將自動帶入上一期的答案。</span>
            <form id="create_form" action="{{ route('school_regular_report.store') }}" method="post" onsubmit="return false">
            @csrf
            <input type="hidden" name="regular_report_school_id" value="{{ $regular_report_school->id }}">            
            <input type="hidden" name="regular_report_id" value="{{ $regular_report_school->regular_report_id }}">
            @include('edus.regular_reports.sample_'.$sample_num)    
            <table>
                <tr>
                    <td>
                        <button type="button" id="closeVeno" class="btn btn-secondary btn-sm">關閉視窗</button>
                    </td>
                    <td>
                        <button class="btn btn-success btn-sm" onclick="sw_confirm2('確定嗎？若無法送出，請檢查是否有無未填題目！','create_form')">送出</button>
                    </td>
                    <td>
                        <span class="btn btn-dark btn-sm" onclick="sw_confirm3('確定嗎？會覆蓋之前的暫存檔喔！',go_save_temp)"><i class="fas fa-save"></i> 暫存</span>
                    </td>
                    <td>
                        <?php
                            $check_regular_report_temp = \App\Models\RegularReportTemp::where('code','like', "%".auth()->user()->code."%")->where('regular_report_id',$regular_report_school->regular_report_id)->first();
                        ?>
                        <div id="show_pull">
                            @if($check_regular_report_temp)
                                <span class="btn btn-outline-secondary btn-sm" onclick="sw_confirm3('確定嗎？會覆蓋目前填入的資料喔！',pull_temp)"><i class="fas fa-download"></i> 拉下暫存</span>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>   
            </form>                            
        </div>
    </div>
</div>
<form id="pull_form">
    @csrf
</form>
<script>        
    function go_save_temp(){
        $.ajax({
            url: '{{ route('school_regular_report.save_temp') }}',
            type : 'post',
            dataType : 'json',
            data : $('#create_form').serialize(),
            success : function(result) {
                sw_alert('暫存成功');
                show_pull();
            },
            error: function() {
                sw_alert('暫存失敗！');
            }
        })

    }

    function show_pull(){
        document.getElementById('show_pull').innerHTML = '<span class="btn btn-outline-secondary btn-sm" onclick="sw_confirm3(\'確定嗎？會覆蓋目前填入的資料喔！\',pull_temp);"><i class="fas fa-download"></i> 拉下暫存</span>';                                                        
    }

    function pull_temp(){
        $.ajax({
            url: '{{ route('school_regular_report.pull_temp',$regular_report_school->regular_report_id) }}',
            type : 'post',
            dataType : 'json',
            data : $('#pull_form').serialize(),
            success : function(result) {
                sw_alert('拉下暫存成功');
                insert_temp(result);
            },
            error: function() {
                sw_alert('拉下暫存失敗！');
            }
        })
    }

    function insert_temp(result) {
    for (var k in result) {
        // 💡 透過 name 屬性尋找 input 元素（例如 input[name="go_bike_count"]）
        var inputElement = document.querySelector('input[name="' + k + '"]');
        
        // 防呆：確保頁面上真的有這個 input 元素才塞值，避免報錯
        if (inputElement) {
            inputElement.value = result[k]; // result[k] 就等於 27
        }
    }

    // ✨ 還記得我們上一題寫的自動加總機制嗎？
    // 當你用 JS 批量塞入新數值後，必須手動觸發一次 input 事件，
    // 這樣後方的「合計」跟「百分比」才會在一載入時立刻跟著算好！
    if (typeof $ !== 'undefined') {
        $('input[name="go_walk_count"]').trigger('input');
        $('input[name="back_walk_count"]').trigger('input');
        $('input[name="park_bike"]').trigger('input');
        $('input[name="guide_bike"]').trigger('input');
    }
}
    
</script>
@endsection