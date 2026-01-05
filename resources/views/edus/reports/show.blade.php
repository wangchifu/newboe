@extends('layouts.app_clean')

@section('title',$report->title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm my-3">
                    <div class="card-head">
                        <img class="card-img-top img-responsive" src="{{ asset('images/small/report.png') }}">
                    </div>
                    <div class="card-body">
                        <h2>
                            編號：{{ $report->id }}<br>
                            @if($report->situation !=4)
                                {{ $report->name }}                            
                            @else                       
                                <span style="color:red">[填報作廢]</span>     
                                <del>{{ $report->name }}</del>
                            @endif
                        </h2>
                        {{ $sections[auth()->user()->section_id] }} / {{ $report->user->name }} / {{ $report->created_at }} 創建
                        @if(!empty($report->user->telephone))
                            <i class="fas fa-phone"></i> {{ $report->user->telephone }}
                        @endif
                        <hr>
                        <strong>截止日期：</strong>
                        <div class="form-group text-danger">
                            {{ $report->die_date }}
                        </div>
                        <strong>說明：</strong>
                        <div class="form-group">
                            {!! $report->content !!}
                        </div>
                        <strong>附檔：</strong>
                        <div class="form-group">
                            @foreach($files as $k=>$v)
                                <a href="{{ route('edu_report.download',['id'=>$report->id,'filename'=>$v]) }}" class="btn btn-primary btn-sm" style="margin:3px"><i class="fas fa-download"></i> {{ $v }}</a>
                            @endforeach
                        </div>
                        <strong>題目：</strong>
                        <div class="form-group">
                            <?php  $i=1; ?>
                            @foreach($report->questions as $question)
                                <div class="card" style="margin: 5px;">
                                    <div class="card-header">
                                        題目{{ $i }}：
                                        {{ $question->title }}
                                    </div>
                                    <div class="card-body">
                                        @if($question->type=="radio" or $question->type=="checkbox")
                                            <?php $options = unserialize($question->options); ?>
                                                @if($question->type=="radio")
                                                    <strong>單選選項：</strong>
                                                @elseif($question->type=="checkbox")
                                                    <strong>多選選項：</strong>
                                                @endif
                                                <br>
                                            @foreach($options as $k=>$v)
                                                <span>
                                                    @if($question->type=="radio")
                                                        <?php $checked=($k==0)?"checked":""; ?>
                                                        <input type="radio" name="radio" id="id{{ $question->id }}{{ $k }}" {{ $checked }}>
                                                    @elseif($question->type=="checkbox")
                                                        <input type="checkbox" name="checkbox" id="id{{ $question->id }}{{ $k }}">
                                                    @endif
                                                    <label for="id{{ $question->id }}{{ $k }}">{{ $v }}</label>
                                                </span><br>
                                            @endforeach
                                        @elseif($question->type=="text")
                                            <input type="text" placeholder="填寫文字">
                                        @elseif($question->type=="num")
                                            <input type="text" class="only-number" placeholder="填寫數字">
                                        @endif
                                    </div>
                                </div>
                            <?php $i++; ?>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <strong>對象學校：</strong>
                            <br>
                            @foreach($schools as $school)
                                <span>{{ $school->school_name }}</span>,
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
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