@extends('layouts.app')

@section('title','公告的特殊處理')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>公告的特殊處理</h1>
    <div class="card mb-4">
        <div class="card-header">特殊處理 - 處理公告</div>
        <div class="card-body">                        
            @if($post)
            <h2>公告 ID：{{ $post->id }}</h2>
            <h3>
                [{{ $post->post_no }}]{{ $post->title }}
            </h3>
            {!! $post->content !!}
            <hr>
            <span class="text-danger">含給各校的 post_school 共 {{ count($post_schools) }} 校。</span>
            <form action="{{ route('admins.special_post_delete') }}" method="post" id="post_form" onsubmit="return false">
                @csrf 
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <button type="submit" class="btn btn-danger btn-sm" onclick="sw_confirm2('確定嗎？完全無法救回喔！','post_form')">
                    <i class="fas fa-trash-alt"></i> 刪除此公告及其已發給各校的 post_school
                </button>
            </form>                                               
            @else
                無此公告
            @endif
            <br>
            <a href="{{ route('admins.special') }}" class="btn btn-secondary"><i class="fas fa-backward"></i> 返回</a>            
        </div>
    </div>           
</div>
@endsection