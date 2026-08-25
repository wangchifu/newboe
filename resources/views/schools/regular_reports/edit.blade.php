@extends('layouts.app_clean')

@section('title','編輯定期填報內容')

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
            <span class="text-danger">* 每題都是必填，若題目不合，請電洽縣府承辦人，或填「無、0」。</span>
            <form id="create_form" action="{{ route('school_regular_report.update',['regular_report_school'=>$regular_report_school->id]) }}" method="post" onsubmit="return false">
            @csrf
            @method('patch')
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
                </tr>
            </table>   
            </form>                            
        </div>
    </div>
</div>
<form id="pull_form">
    @csrf
</form>
@endsection