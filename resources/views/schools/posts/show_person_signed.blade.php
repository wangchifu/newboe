@extends('layouts.app')

@section('title','公告簽收')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">

</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>公告簽收 </h1>
    <div class="card mb-4">
        <div class="card-header">
            <a class="btn btn-success btn-sm" href="{{ route('posts.showSigned') }}">公告簽收 ({{ session('posts_not') }})</a>
            <a class="btn btn-light btn-sm" href="{{ route('school_report.index') }}">資料填報 ({{ session('reports_not') }})</a>
            <a class="btn btn-light btn-sm" href="{{ route('school_regular_report.index') }}">定期資料填報 ({{ session('regular_reports_not') }})</a>
        </div>
        <div class="card-body">                   
            @include('schools.posts.search_nav',['section_id'=>'all'])
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.showSigned') }}">全部 ({{ session('posts_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.show_not_Signed') }}">未簽收 ({{ session('posts_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.show_quick_Signed') }}">最速件 ({{ session('posts_quick') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('posts.show_person_Signed') }}">個人已簽收</a>
                </li>
            </ul>
            <div class="table-responsive">
                <table class="table rwd-table table-hover" style="word-break: break-all;">
                    <thead thead-light>
                    <tr>
                        <th nowrap>
                            編號
                        </th>
                        <th nowrap>
                            對象
                        </th>
                        <th nowrap>
                            主旨
                        </th>
                        <th nowrap>
                            發佈人
                        </th>
                        <th nowrap>
                            發佈日期
                        </th>
                        <th nowrap>
                            簽收                                    
                        </th>                        
                    </tr>                            
                    </thead>                    
                    <tbody>
                        @foreach($post5 as $post)
                        <tr>
                            <td class="td-number" data-th="編號">
                                <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$post->code] }}">{{ $post->post_no }}</span>
                            </td>
                            <td data-th="對象" class="td-school-name">
                                <?php
                                    $school = \App\Models\School::where('code_no',$post->code)->first();
                                ?>
                                {{ $school->school_name }}
                            </td>
                            <td class="td-title" data-th="主旨">
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
                                @if( $post->situation ===4 )
                                    <span style="color:red">[公告作廢]</span> <strike class="text-primary"><a href="{{ route('posts.show',[$post->id,$post->ps_id]) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">{{ $post->title }}</a></strike>
                                @else
                                    <a href="{{ route('posts.show',[$post->id,$post->ps_id]) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                                        <span style="color:#000088">
                                            {{ $post->title }}
                                        </span>
                                    </a>
                                @endif
                            </td>
                            <td nowrap data-th="發佈人">
                                {{ array_get($sections,$post->section_id) }}<br>{{ $post->name }}
                            </td>
                            <td nowrap data-th="發佈日期">
                                <small>
                                    {{ substr($post->passed_at,0,10) }}<br>{{ substr($post->passed_at,11,5) }}
                                </small>
                            </td>
                            <td nowrap data-th="簽收">                            
                            @if($post->signed_at != null)
                                {{ userid2name($post->signed_user_id) }}
                            @endif
                            </td>                            
                        </tr>
                    @endforeach                   
                    </tbody>                    
                </table>
            </div>                                    
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                <div class="text-center">
                    {{ $post5->links('layouts.simple-pagination') }}                    
                </div>
            </div>                            
        </div>
    </div>
</div>
@endsection