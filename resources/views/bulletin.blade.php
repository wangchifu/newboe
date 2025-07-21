@extends('layouts.app')

@section('title','公告系統')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1 class="text-center">{{ $category }}</h1>
    <div class="card mb-4">
        <div class="card-header text-center bg-light">
            <?php
            $key = rand(100,999);
            session(['search' => $key]);
            ?>
            <form action="{{ route('bulletin_search') }}" method="post" id="this_form">
                @csrf
                發佈人/主旨/內文：<input type="text" name="want" required placeholder="關鍵字">
                <input type="text" name="check" placeholder="請輸入：{{ session('search') }}" required maxlength="3">
                <input type="hidden" name="category_id" value="{{ $category_id }}">
                <input class="btn btn-primary btn-sm" type="submit" value="搜尋">
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