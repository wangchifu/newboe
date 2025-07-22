@extends('layouts.app')

@section('title','公告系統')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection
<?php
$outline0 = ($category_id==0)?"outline-":null;
$outline1 = ($category_id==1)?"outline-":null;
$outline2 = ($category_id==2)?"outline-":null;
$outline3 = ($category_id==3)?"outline-":null;
$outline4 = ($category_id==4)?"outline-":null;
$outline5 = ($category_id==5)?"outline-":null;
?>
@section('content')
<div class="col-lg-12 mx-auto">
    <h1 class="text-center">{{ $category }}</h1>
    <a href="{{ route('bulletin.show',[0]) }}" class="btn btn-{{ $outline0 }}primary btn-sm" >全部公告</a>
    <a href="{{ route('bulletin.show',[1]) }}" class="btn btn-{{ $outline1 }}success btn-sm">一般公告</a>
    <a href="{{ route('bulletin.show',[2]) }}" class="btn btn-{{ $outline2 }}info btn-sm">競賽訊息</a>
    <a href="{{ route('bulletin.show',[3]) }}" class="btn btn-{{ $outline3 }}secondary btn-sm">活動成果</a>
    <a href="{{ route('bulletin.show',[4]) }}" class="btn btn-{{ $outline4 }}dark btn-sm">新聞快訊</a>
    <a href="{{ route('bulletin.show',[5]) }}" class="btn btn-{{ $outline5 }}warning btn-sm">公開的行政公告</a>
    <div class="card mb-4">
        <div class="card-header text-center bg-light">
            <?php
            $key = rand(100,999);
            session(['search' => $key]);
            ?>
            <form action="{{ route('bulletin_search') }}" method="post" id="this_form">
                @csrf
                <table class="mx-auto">
                    <tr>
                        <td>
                            發佈人/主旨/內文：
                        </td>
                        <td>
                            <input type="text" class="form-control" name="want" required placeholder="關鍵字">
                        </td>
                        <td>
                            <input type="text" class="form-control" name="check" placeholder="請輸入：{{ session('search') }}" required maxlength="3">
                        </td>
                        <td>
                            <input type="hidden" name="category_id" value="{{ $category_id }}">
                            <input class="btn btn-primary btn-sm" type="submit" value="搜尋">
                        </td>                        
                    </tr>
                </table>                                                
            </form>
            @include('layouts.errors')
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>主旨</th>
                    <th nowrap>發佈時間</th>
                </thead>
                <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>
                            <a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe">{{ $post->title }}</a>
                        </td>
                        <td nowrap>
                            {{ substr($post->passed_at,0,16) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer d-flex flex-row justify-content-center">
            <div class="pt-3">{{ $posts->links('layouts.pagination') }}</div>
        </div>
    </div>
</div>
<script>
    var vb = new VenoBox({
        selector: '.venobox',
        numeration: true,
        infinigall: true,
        //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
        spinner: 'rotating-plane'
    });

    $(document).on('click', '.vbox-close', function() {
        vb.close();
    });

</script>
@endsection