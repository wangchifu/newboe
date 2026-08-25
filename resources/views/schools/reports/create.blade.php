@extends('layouts.app_clean')

@section('title','編輯填報內容')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card shadow-sm my-3">
        <div class="card-header">            
            <img class="card-img-top img-responsive" src="{{ asset('images/small/school_report.png') }}">
        </div>
        <div class="card-body">
            <span class="text-right">
                {{ $sections[$report_school->report->section_id] ?? '' }} /
                {{ $report_school->report->user->name }} / 
                @if(!empty($report_school->report->user->telephone)) / 
                    <i class="fas fa-phone"></i> {{ $report_school->report->user->telephone }} / 
                @endif                
                {{ $report_school->report->passed_at }} 發佈 /
                <span class="text-danger">{{ $report_school->report->die_date }} 截止</span> /                 
            </span>
            <h4>
                @if( $report_school->report->situation !=4)
                    {{ $report_school->report->name }}
                @else
                    <span style="color:red">[填報作廢]</span>
                    <strike class="text-primary">
                        {{ $report_school->report->name }}
                    </strike></a> 
                @endif
            </h4>
            @if(!empty($report_school->report->content))
                <div class="form-group">
                    <strong>說明：</strong><br>
                    {!! $report_school->report->content !!}
                </div>
            @endif
            <?php
            $files = get_files(storage_path('app/public/report_files/' . $report_school->report->id));
            ?>
            @if(!empty($files))
                <div class="form-group">
                    <strong>附檔：</strong><br>
                    @foreach($files as $k=>$v)
                        <a href="{{ route('edu_report.download',['id'=>$report_school->report->id,'filename'=>$v]) }}" class="btn btn-primary btn-sm" style="margin:3px"><i class="fas fa-download"></i> {{ $v }}</a>
                    @endforeach
                </div>
            @endif
            <hr>
            <h4 class="text-danger">題目與填報</h4>
            @include('layouts.errors')
            <span class="text-danger">* 每題都是必填，若題目不合，請電洽縣府承辦人，或填「無、0」。</span>
            <form id="create_form" action="{{ route('school_report.store') }}" method="post" onsubmit="return false">
            @csrf
            <input type="hidden" name="report_school_id" value="{{ $report_school->id }}">
            <input type="hidden" name="report_id" value="{{ $report_school->report_id }}">
            <?php $i=1; ?>
            @foreach($report_school->report->questions as $question)
                <div class="form-group my-2">
                    <label for="title{{ $question->id }}"><strong><span class="text-danger text-bold">*</span> 題目{{ $i }}：{{ $question->title }}</strong></label>
                    <?php
                        if($question->type=="radio" or $question->type=="checkbox"){
                            $options = unserialize($question->options);
                        }
                    ?>
                    @if($question->type=="radio")
                        <br>
                        @foreach($options as $k=>$v)
                            <?php $checked=($k==0)?"checked":""; ?>
                            <span>
                                <input type="radio" name="answer[{{ $question->id }}]" id="id{{ $question->id }}{{ $k }}" {{ $checked }} value="{{ $v }}">
                            </span>
                            <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                            <br>
                        @endforeach
                    @elseif($question->type=="checkbox")
                        <br>
                        @foreach($options as $k=>$v)
                            <span>
                                <input type="checkbox" name="answer_checkbox{{ $question->id }}[]" id="id{{ $question->id }}{{ $k }}" value="{{ $v }}">
                            </span>
                            <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                            <br>
                        @endforeach
                    @elseif($question->type=="text")
                        <input type="text" name="answer[<?= $question->id ?>]" id="title<?= $question->id ?>" class="form-control" placeholder="請填寫文字" required>
                    @elseif($question->type=="num")
                        <input type="text" name="answer[<?= $question->id ?>]" id="title<?= $question->id ?>" class="form-control only-number" placeholder="只能填寫數字" required>
                    @endif
                    <br>
                </div>
            <?php $i++; ?>
                <input type="hidden" name="type[{{ $question->id }}]" value="{{ $question->type }}">
            @endforeach  
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
                            $check_report_temp = \App\Models\ReportTemp::where('code','like', "%".auth()->user()->code."%")->where('report_id',$report_school->report_id)->first();
                        ?>
                        <div id="show_pull">
                            @if(!empty($check_report_temp))
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
    document.querySelectorAll('.only-number').forEach(function (el) {
        el.addEventListener('input', function () {
            let v = this.value;

            // 清掉非數字、負號、小數點
            v = v.replace(/[^0-9\.-]/g, '');

            // 只保留開頭的 -
            v = v.replace(/(?!^)-/g, '');

            // 只保留第一個小數點
            v = v.replace(/(\..*)\./g, '$1');

            this.value = v;
        });
    });
    
    function go_save_temp(){
        $.ajax({
            url: '{{ route('school_report.save_temp') }}',
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
            url: '{{ route('school_report.pull_temp',$report_school->report_id) }}',
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

    function insert_temp(result){
        for (var k in result) {
            if(result[k]['type'] == 'text' || result[k]['type'] == 'num'){
                document.getElementById('title'+k).value = result[k]['answer'];
            }
            if(result[k]['type'] == 'radio' || result[k]['type'] == 'checkbox'){
                for(var k1 in result[k]['options']){
                    if(document.getElementById('id'+k+k1).value == result[k]['answer']){
                        document.getElementById('id'+k+k1).checked = true;
                    }
                }
            }
            if(result[k]['type'] == 'checkbox'){
                for(var k1 in result[k]['options']){
                    for(var k2 in result[k]['answer']){
                        if(document.getElementById('id'+k+k1).value == result[k]['answer'][k2]){
                            document.getElementById('id'+k+k1).checked = true;
                        }
                    }
                }
            }
        }
    }
    
</script>
@endsection