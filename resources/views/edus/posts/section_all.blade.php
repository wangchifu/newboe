@extends('layouts.app')

@section('title',$sections[auth()->user()->section_id].'全數公告')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('my_meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>[{{ $sections[auth()->user()->section_id] }}]：<span class="badge border border-dark text-dark bg-white"><i class="fas fa-list"></i> 全數公告</span></h1>
        @include('edus.posts.nav')                 
        <div class="card my-4">
            <div class="card-header">
                <form action="{{ route('posts.do_search_in_section') }}" method="post" id="this_form">
                    @csrf                    
                    發佈人/主旨/內文：<input type="text" name="want" required placeholder="關鍵字" value="{{ $want }}">
                    <input type="submit" value="搜尋" class="btn btn-success btn-sm">
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table rwd-table table-hover" style="word-break: break-all;">
                        <thead class="thead-light">
                        <tr>
                            <th nowrap>
                                編號
                            </th>
                            <th nowrap>
                                類別
                            </th>
                            <th nowrap>
                                發佈人
                            </th>
                            <th nowrap>
                                主旨
                            </th>
                            <th nowrap>
                                創建時間<br>發佈時間
                            </th>
                            <th nowrap>
                                狀態
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <td data-th="編號" nowrap class="td-number">
                                    @if($post->post_no)
                                        {{ $post->post_no }}
                                    @else
                                        {{ $post->id }}
                                    @endif
                                </td>
                                <td data-th="類別" nowrap>
                                    {{ $categories[$post->category_id] }}
                                </td>
                                <td data-th="發佈人">
                                    {{ $sections[$post->section_id] }}<br>
                                    {{ $post->user->name }}
                                </td>
                                <td data-th="主旨" class="td-title">
                                    @if($post->another ===1)
                                        <span class="text-success">
                        <i class="fas fa-eye"></i>
                    </span>
                                    @endif
                                    @if($post->type ===1)
                                        <span class="text-danger">
                        [最速件]
                    </span>
                                    @endif
                                    @if( $post->situation ===4)
                                            <a href="{{ route('posts.show_doing_post',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                        <span
                            style="color:red">[公告作廢]
                        </span>
                                            <strike class="text-primary">
                                                {{ $post->title }}
                                            </strike></a>
                                    @else
                                            <a href="{{ route('posts.show_doing_post',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                        <span style="color:#000088">
                        {{ $post->title }}
                        </span>
                                        </a>
                                    @endif
                                </td>
                                <td data-th="創建時間" nowrap>
                                    {{ substr($post->created_at,0,16) }}<br>
                                    {{ substr($post->passed_at,0,16) }}
                                </td>
                                <td data-th="狀態" nowrap>
                                    {{ $situation[$post->situation] }}
                                    @if($post->situation ==3 and $post->user_id == auth()->user()->id)
                                        <a href="{{ route('posts.copy',$post->id) }}" class="btn btn-outline-primary btn-sm">
                                            複製
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>                           
                </div>      
            </div>
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                {{ $posts->links('layouts.pagination') }}
            </div>
        </div>
    </div>    
</div>
@endsection