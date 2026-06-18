@extends('layouts.app_clean')

@section('title','定期填報內容')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card shadow-sm my-3">
        <div class="card-header">            
            <img class="card-img-top img-responsive" src="{{ asset('images/small/regular.jpeg') }}">
        </div>
        <div class="card-body">
            <span class="text-right">
                {{ $sections[$regular_report_school->regular_report->section_id] }} / 
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
                    {{ $regular_report_school->regular_report->regular_sample->name }}
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
            <span class="text-danger">* 每題都是必填，若題目不合，請電洽縣府承辦人，或填「無、0」。</span>            
            @include('edus.regular_reports.sample_'.$sample_num)                              
        </div>
        <div class="card-footer">
                填報者：
            @if(!empty($regular_report_school->signed_user_id))
                {{ userid2name($regular_report_school->signed_user_id) }}
            @else
                <span class="text-danger">尚未填寫</span>
            @endif
            <br>
                填報日期：{{ $regular_report_school->signed_at }}
            <br>
            @if($regular_report_school->review_user_id)
                審核者：{{ $regular_report_school->review_user->name }}
                <br>
                審核日期：{{ $regular_report_school->updated_at }}
            @endif   
            <div class="py-3 text-center">
                <button type="button" id="closeVeno" class="btn btn-secondary">
                    關閉視窗
                </button>
                <a class="btn btn-outline-primary mx-1" href="{{ route('school_regular_report.print2',$regular_report_school->id) }}" target="_blank">
                    <i class="fas fa-print"></i> 列印填報
                </a>
            </div> 
        </div>
    </div>
</div>
@endsection