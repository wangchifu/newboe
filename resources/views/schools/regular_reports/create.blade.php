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
        // 取得暫存按鈕並暫時禁用，避免重複連點
        var $btn = $(event.currentTarget);
        $btn.prop('disabled', true);

        $.ajax({
            url: '{{ route('school_regular_report.save_temp') }}',
            type : 'post',
            dataType : 'json',
            data : $('#create_form').serialize(),
            success : function(result) {
                sw_alert('暫存成功');
                show_pull();

                // 💡 關鍵修復：更新表單內的 _token 為最新 Token，避免第二次暫存報 419
                if (result.new_token) {
                    $('#create_form input[name="_token"]').val(result.new_token);
                }
            },
            error: function(xhr) {
                if (xhr.status === 419) {
                    sw_alert('頁面閒置過久已過期 (419)，請重新整理 (F5) 頁面後再試！');
                } else {
                    sw_alert('暫存失敗！');
                }
            },
            complete: function() {
                // 請求完成後恢復按鈕
                $btn.prop('disabled', false);
            }
        });
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
            // 💡 防呆：如果是 CSRF Token 直接跳過，不寫入表單
            if (k === '_token') {
                continue;
            }

            // 💡 關鍵修復：改用 CSS 選擇器同時匹配 input 與 select
            var formElement = document.querySelector('[name="' + k + '"]');
            
            // 防呆：確保頁面上真的有這個元素才塞值
            if (formElement) {
                formElement.value = result[k]; // select 只要 value 與 option 對應就會自動預選
            }
        }

        // 當用 JS 批量塞入新數值後，觸發事件讓相關計算與畫面跟著更新
        if (typeof $ !== 'undefined') {
            $('input[name="go_walk_count"]').trigger('input');
            $('input[name="back_walk_count"]').trigger('input');
            $('input[name="park_bike"]').trigger('input');
            $('select[name="guide_bike"]').trigger('change');
        }
    }
    
</script>
@endsection