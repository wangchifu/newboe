@extends('layouts.app')

@section('title','搜尋公告簽收')

@section('header')
<header class="py-5 bg-light border-bottom mb-4">    
</header>
@endsection

@section('content')
<div class="col-lg-12 mx-auto">
    <h1>公告簽收 </h1>
    <div class="card mb-4">
        <div class="card-header">
            <a href="{{ route('posts.showSigned') }}">公告列表</a> / 搜尋：「{{ $want }}」
        </div>
        <div class="card-body">  
            @include('schools.posts.search_nav',['section_id'=>'all'])
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
                    @if(count($posts))
                        @foreach($posts as $post)
                        <tr>
                            <td nowrap>
                                {{ $post->post_no }}
                            </td>
                            <td data-th="對象" class="td-school-name">
                                <?php
                                    $school = \App\Models\School::where('code_no',$post->code)->first();
                                ?>
                                {{ $school->school_name }}
                            </td>
                            <td>
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
                                    <span style="color:red">[公告作廢]</span> <strike class="text-primary"><a href="{{ route('posts.show',$post->id) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">{{ $post->title }}</a></strike>
                                @else                                    
                                    <a href="{{ route('posts.show',[$post->id,$post->ps_id]) }}" class="venobox" data-vbtype="iframe" style="text-decoration: none; color: inherit;">
                                        <span style="color:#000088">
                                            {{ $post->title }}
                                        </span>
                                    </a>
                                @endif
                            </td>
                            <td nowrap>                                
                                {{ array_get($sections,$post->section_id) }}<br>{{ userid2name($post->user_id) }}
                            </td>                            
                            <td nowrap>
                                <small>
                                    {{ substr($post->passed_at,0,10) }}<br>{{ substr($post->passed_at,11,5) }}
                                </small>
                            </td>
                            <td nowrap>
                                @if($post->signed_at==null and $post->situation != 4)
                                <form action="{{ route('posts.signed', ['ps_id' => $post->ps_id]) }}" method="POST" id="sign_check_form{{ $post->id }}">
                                    @method('PATCH')
                                    @csrf

                                    <input type="hidden" value="{{ $user_power -> power_type }}"
                                            id="h_user_power">

                                    <button class="btn btn-success btn-sm" type="button"  onclick="sw_confirm2('您確定要簽收 編號 {{ $post->post_no }}？','sign_check_form{{ $post->id }}');">
                                        簽收
                                    </button>                                        
                                </form>                                    
                                @endif
                                @if($post->signed_at != null)
                                    {{ userid2name($post->signed_user_id) }}
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <p class="text-danger">查無資料！</p>
                    @endif
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex flex-row justify-content-center pt-4">
                <div class="text-center">
                    {{ $posts->appends(['want' => $want])->links('layouts.simple-pagination') }}                   
                </div>
            </div>          
        </div>
    </div>
</div>
@endsection