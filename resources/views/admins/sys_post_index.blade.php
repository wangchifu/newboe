@extends('layouts.app')

@section('title','系統公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>系統公告</h1>
    <div class="card mb-4">
        <div class="card-header">全站帳號都會收到</div>
        <div class="card-body">            
            <form action="{{ route('admins.sys_post_store') }}" method="post" id="sys_form">
                @csrf
                <div class="form-group">
                    <label class="text-danger"><strong>內容*</strong></label>
                    <textarea name="content" class="form-control" required></textarea>
                </div>   
                <div class="form-group">
                    <label class="text-danger"><strong>開始日期*</strong></label>
                    <input type="date" name="start_date" value="{{ date('Y-m-d') }}" class="form-control" required>
                </div> 
                <div class="form-group">
                    <label class="text-danger"><strong>結束日期*</strong></label>
                    <input type="date" name="end_date" value="" class="form-control" required>
                </div>
                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                <div class="form-group">
                    <a class="btn btn-primary btn-sm" onclick="sw_confirm2('確定送出？','sys_form')">送出</a>
                </div>
            </form>
            <hr>                                                
            <h4>已公告列表</h4>
            @foreach($system_posts as $system_post)
                <div class="card">
                    <div class="card-header" style="background-color: #FFCC22">
                        <small>[<a href="#!" class="text-danger" onclick="sw_confirm1('確定刪除嗎？','{{ route('admins.sys_post_destroy',$system_post->id) }}')">刪除</a>]</small> ({{ $system_post->id }}) 開始：{{ $system_post->start_date }} 結束：{{ $system_post->end_date }}
                    </div>
                    <div class="card-body" style="background-color: #FFFFBB">
                        {!! nl2br($system_post->content) !!}
                    </div>
                </div>
                <br>
            @endforeach
            <div style="text-align:right">
                {{ $system_posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection