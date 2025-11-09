@extends('layouts.app_clean')

@section('title','編輯填報內容')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card shadow-sm my-3">
        <div class="card-header">                        
            <img class="card-img-top img-responsive" src="{{ asset('images/small/school_report_edit.png') }}">
        </div>
        <div class="card-body">
            <span class="text-right">
                {{ $sections[$report_school->report->section_id] }} / 
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
            <form action="{{ route('school_report.update') }}" method="post" onsubmit="return false" id="edit_form">
            @csrf
            @method('patch')
            <input type="hidden" name="report_school_id" value="{{ $report_school->id }}">
            <?php $i=1; ?>
            @foreach($report_school->report->questions as $question)
                <div class="form-group my-2">
                    <label for="title{{ $question->id }}"><strong>題目{{ $i }}：{{ $question->title }}</strong></label>
                    <?php
                    if($question->type=="radio" or $question->type=="checkbox"){
                        $options = unserialize($question->options);
                    }
                    ?>
                    @if($question->type=="radio")
                        <br>
                        @foreach($options as $k=>$v)
                            <?php
                        if(isset($answer_data[$question->id])){
                $checked = ($answer_data[$question->id] == $v)?"checked":"";
            }else{
                    $checked = "";
            }
                            ?>
                            <span>
                            <input type="radio" name="answer[{{ $question->id }}]" id="id{{ $question->id }}{{ $k }}" {{ $checked }} value="{{ $v }}">
                        </span>
                            <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                            <br>
                        @endforeach
                    @elseif($question->type=="checkbox")
                        <br>
                        @foreach($options as $k=>$v)
                            <?php
                                if(isset($answer_data[$question->id])){
                                    $answer_array = explode(',',$answer_data[$question->id]);
                                }else{
                                    $answer_array = [];
                                }

                            $checked = (in_array($v,$answer_array))?"checked":"";
                            ?>
                            <span>
                            <input type="checkbox" name="answer_checkbox{{ $question->id }}[]" id="id{{ $question->id }}{{ $k }}" value="{{ $v }}" {{ $checked }}>
                        </span>
                            <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                            <br>
                        @endforeach
                    @elseif($question->type=="text")
                        @if(isset($answer_data[$question->id]))
                            <input type="text" name="answer[<?= $question->id ?>]" id="title<?= $question->id ?>" class="form-control" required value="<?= $answer_data[$question->id] ?>">
                        @else
                            <input type="text" name="answer[<?= $question->id ?>]" id="title<?= $question->id ?>" class="form-control" placeholder="請填寫文字" required>
                        @endif
                    @elseif($question->type=="num")
                        @if(isset($answer_data[$question->id]))
                            <input type="text" name="answer[<?= $question->id ?>]" id="title<?= $question->id ?>" class="form-control only-number" placeholder="只能填寫數字" required value="<?= $answer_data[$question->id] ?>">
                        @else
                            <input type="text" name="answer[{{ $question->id }}]" id="title{{ $question->id }}" class="form-control only-number" placeholder="只能填寫數字" required value="{{ $answer_data[$question->id] ?? '' }}">
                        @endif
                    @endif
                </div>
                <?php $i++; ?>
                <input type="hidden" name="type[{{ $question->id }}]" value="{{ $question->type }}">
            @endforeach
            <button class="btn btn-success" onclick="sw_confirm2('確定嗎？','edit_form')">送出</button>                    
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
    
</script>
@endsection