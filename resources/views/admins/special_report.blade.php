@extends('layouts.app')

@section('title','填報的特殊處理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>填報的特殊處理</h1>
    <div class="card mb-4">
        <div class="card-header">特殊處理 - 處理填報</div>
        <div class="card-body">                        
            @if($report)
            <h2>填報 ID：{{ $report->id }}</h2>
            <h3>
                {{ $report->name }} }}
            </h3>
            {!! $report->content !!}
            <hr>
            <span class="text-danger">含給各校的 report_school 共 {{ count($report_schools) }} 校。</span><br>
            <span class="text-danger">含問題 questions 共 {{ count($questions) }} 題。</span><br>
            <span class="text-danger">含各校的 answers 回答 共 {{ count($answers) }} 則。</span><br>
            <form action="{{ route('admins.special_report_delete') }}" method="post" id="report_form" onsubmit="return false">
                @csrf 
                <input type="hidden" name="report_id" value="{{ $report->id }}">
                <button type="submit" class="btn btn-danger btn-sm" onclick="sw_confirm2('確定嗎？完全無法救回喔！','report_form')">
                    <i class="fas fa-trash-alt"></i> 刪除此填報及其已發給各校的 report_school 及其問題與學校的答案
                </button>
            </form>                                               
            @else
                無此填報
            @endif
            <br>
            <a href="{{ route('admins.special') }}" class="btn btn-secondary"><i class="fas fa-backward"></i> 返回</a>            
        </div>
    </div>           
</div>
@endsection