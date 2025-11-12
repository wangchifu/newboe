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
        </div>
        <div class="card-body">                   
            @include('schools.posts.search_nav',['section_id'=>'all'])
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('posts.showSigned') }}">全部 ({{ session('posts_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.show_not_Signed') }}">未簽收 ({{ session('posts_not') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.show_quick_Signed') }}">最速件 ({{ session('posts_quick') }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('posts.show_person_Signed') }}">個人已簽收</a>
                </li>
            </ul>
            <div class="table-responsive">
                @include('layouts.errors')
                <table class="table rwd-table table-hover" style="word-break: break-all;">
                    <thead thead-light>
                    <tr>
                        <th nowrap>
                            編號
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
                        <th nowrap>
                            列印
                        </th>
                    </tr>                            
                    </thead>
                    <tr>
                        <th colspan="6" class="text-right" style="text-align: right;"><button onclick="sw_confirm3('您確定打勾的都要簽收嗎?', more_post)">打勾者批次簽收</button></th>
                    </tr>
                    <tbody>
                        @foreach($post_schools as $post_school)
                        <tr>
                            <td class="td-number">
                                <span data-toggle="tooltip" data-placement="top" title="給 {{ $schools[$post_school->code] }}">{{ $post_school->post->post_no }}</span>
                            </td>

                            <td class="td-title">
                                @if($post_school->post->another ===1)
                                    <span class="text-success">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                @endif
                                @if($post_school->post->type ===1)
                                    <span class="text-danger">
                                        [最速件]
                                    </span>
                                @endif
                                @if( $post_school->post->situation ===4 )
                                    <span style="color:red">[公告作廢]</span> <strike class="text-primary"><a href="{{ route('posts.show',[$post_school->post_id,$post_school->id]) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">{{ $post_school->post->title }}</a></strike>
                                @else
                                    <a href="{{ route('posts.show',[$post_school->post_id,$post_school->id]) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                                        <span style="color:#000088">
                                            {{ $post_school->post->title }}
                                        </span>
                                    </a>
                                @endif
                            </td>
                            <td nowrap>
                                {{ array_get($sections,$post_school->post->section_id) }}<br>{{ $post_school->post->user->name }}
                            </td>
                            <td nowrap>
                                <small>
                                    {{ substr($post_school->post->passed_at,0,10) }}<br>{{ substr($post_school->post->passed_at,11,5) }}
                                </small>
                            </td>
                            <td nowrap>
                            @if($post_school->signed_at==null and $post_school->post->situation != 4)
                            <form action="{{ route('posts.signed', ['ps_id' => $post_school->id]) }}" method="POST" id="sign_check_form{{ $post_school->post_id }}">
                                @method('PATCH')
                                @csrf                                                                       
                                <button class="btn btn-success btn-sm" type="button"  onclick="sw_confirm2('您確定要簽收 編號 {{ $post_school->post->post_no }}？','sign_check_form{{ $post_school->post_id }}');">
                                    簽收
                                </button>
                                <input type="checkbox" name="more_post[{{ $post_school->id }}]" class="more_post" id="m{{ $post_school->id }}"> <label class="small" for="m{{ $post_school->id }}">打勾</label>
                            </form>
                            @endif
                            @if($post_school->signed_at != null)
                                {{ userid2name($post_school->signed_user_id) }}
                            @endif
                            </td>
                            <td data-th="列印">
                                <a href="{{ route('posts.showSigned_print',$post_school->post_id) }}" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-print"></i> <i class="fas fa-sort-amount-up"></i></a>
                            </td>
                        </tr>
                    @endforeach                   
                    </tbody>
                    <tr>
                        <th colspan="6" class="text-right"  style="text-align: right; style="text-align: right;><button onclick="sw_confirm3('您確定打勾的都要簽收嗎?', more_post)">打勾者批次簽收</button></th>
                    </tr>
                </table>
            </div>                        
            <form id="more_post_form" action="{{ route('posts.signed_more') }}" method="post">
                @csrf
                <input name="posts_id" id="more_post_value" type="hidden">
            </form>
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                <div class="text-center">
                    {{ $post_schools->links('layouts.simple-pagination') }}                    
                </div>
            </div>                            
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalLong" tabindex="-1" aria-labelledby="ModalLongTitle" aria-hidden="true" data-mycount="{{ count($posts5_quickly) }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLongTitle">催收公告：</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @foreach($posts5_quickly as $post5_quickly)
                    第{{ $post5_quickly->post->post_no }}號「{{ $post5_quickly->post->title }}....」已逾期，請速簽收
                    <br>
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button>                        
            </div>
        </div>
    </div>
</div>

@if(session('posts_not')>0)
    <script>                
        $(document).ready(function () {
            var yetVisited = localStorage['visited'];

            var mycount = $("#ModalLong").data('mycount');
            if (mycount > 0) {
                if (!yetVisited) {
                    $("#ModalLong").modal('show');
                    //localStorage['visited'] = "yes";
                }
            }
        });
        function more_post(){
            var $boxes = $('.more_post');   
            var posts_id = [];             
            $boxes.each(function(){
                if( $(this).is(':checked') ){
                    var name = $(this).attr('name');
                    var id = parseInt(name.match(/[0-9]+/));                        
                    posts_id.push(id);
                }
            });   
            $('#more_post_value').val(posts_id);
            $('#more_post_form').submit();        
        }        
    </script>
@endif
@endsection