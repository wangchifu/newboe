@extends('layouts.app_clean')

@section('title','公告內容')

@section('content')
<div class="col-lg-12 mx-auto">    
    <div class="card shadow-sm my-3">
        <div class="card-head">
            <img class="card-img-top img-responsive" src="{{ asset('images/posts'.$post->category_id.'.png') }}"
                        alt="{{ array_get($categories,$post->category_id) }}"
                        title="{{ array_get($categories,$post->category_id) }}">
        </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th scope="row" class="text-center post-th" width="20%">[類別] 標題</th>
                                <td style="color: #000000">
                                    @if($post->type===1)
                                        <span class="text-danger">
                                            [最速件]
                                        </span>
                                    @endif
                                    @if($post->post_no)
                                        [{{ $post->post_no }}]
                                    @endif
                                    @if( $post->situation ===4 )
                                        <span style="color:red">[公告作廢]</span> <strike>[{{ array_get($categories,$post->category_id) }}] {{ $post->title }}</strike>
                                    @else
                                        {{ $post->title }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-center post-th" width="20%">單位 / 發佈人</th>
                                <td style="color: #000000">{{ array_get($sections,$post->section_id) }} / 
                                    @auth
                                        {{ $post->user->name }}
                                    @endauth
                                    @guest
                                        {{ $post->user->title }}
                                    @endguest
                                    @if(!empty($post->user->telephone)) <i class="fas fa-phone"></i> {{ $post->user->telephone }}@endif</td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-center post-th" width="20%">時間 / 點閱</th>
                                <td style="color: #000000">{{ substr($post->passed_at,0,16)  }} / {{ $post->views }} </td>
                            </tr>
                            <tr>
                                <th scope="row" class="text-center post-th" width="20%">內容</th>
                                <td style="color: #000000;word-break: break-all;">
                                    @if( $post->situation ===4 )
                                        <strike>{!! $post->content !!}</strike>
                                    @else
                                        {!! $post->content !!}
                                    @endif
                                </td>
                            </tr>
    
                            @if(!empty($files))
                                <tr>
                                    <th scope="row" class="text-center post-th" width="20%">
                                        附加檔案
                                    </th>
                                    <td>
    
                                        @foreach($files as $file)
                                            <a href="{{ route('posts.download',['id'=>$post->id,'filename'=>$file]) }}"
                                               title="點選下載附加檔案({{ $file }})">
                                                {{ $file }}
                                            </a>
                                            <br>
                                        @endforeach
                                    </td>
                                </tr>
                            @endif
    
                            @if(!empty($images))
    
                                <tr>
                                    <th scope="row" class="text-center post-th" width="20%">
                                        相關照片
                                    </th>
                                    <td>
                                        @foreach($images as $image)
                                            <?php
                                            $image_path = $images_path . '/' . $image;
                                            $file_path = str_replace('/', '&', $image_path);
                                            ?>
                                            <a href="{{ asset('storage/post_photos/'.$post->id.'/'.$image) }}" class="venobox" data-gall="gall1">
                                                <img src="{{ route('posts.img',$file_path) }}" class="figure-img img-fluid rounded" width="100px">
                                            </a>
                                        @endforeach
    
                                    </td>
                                </tr>
                            @endif
    
                            @if(!empty($post->url))
                                <tr>
                                    <th scope="row" class="text-center post-th" width="15%">
                                        相關連結
                                    </th>
                                    <td colspan="3">
                                        <a target="_blank" href="{{ $post->url }}" title="點選開啟相關連結網頁">
                                            {{ $post->url }}
                                        </a>
                                    </td>
                                </tr>
                            @endif
    
                        </tbody>
                    </table>
                    @if(!is_null($ps_id))
                        @if($errors->any())
                            <script>
                                opener.location.reload();
                            </script>
                        @endif
                        @if($post_school->signed_at != null)
                            <span class="text-success">
                                {{ userid2name($post_school->signed_user_id) }} 已於 {{ $post_school->signed_at }} 簽收
                            </span>
                        @else
                        <form action="{{ route('posts.signed_at_show', $ps_id) }}" method="POST" id="sign_check_form">
                            @method('PATCH')
                            @csrf                                       
                            <input type="hidden" value="{{ $user_power -> power_type }}" id="h_user_power">
                            <button class="btn btn-success btn-sm" type="button"  onclick="if(confirm('您確定簽收嗎?')) signcheck();else return false">
                                我要簽收
                            </button>
                        </form>
                            <script>
                                function signcheck() {
                                    var user_power = $("#h_user_power").val();
                                    if (user_power == "B") {
                                        $("#sign_check_form").submit();
                                    } else {
                                        alert('您沒有簽收的權限');
                                    }
                                }
                            </script>
                        @endif
                    @endif
                </div>
            </div>
            <div class="card-footer text-center">
                <div>
                    {{ array_get($categories,$post->category_id) }}　{{ array_get($sections,$post->section_id) }}　
                    @auth
                        {{ $post->user->name }}
                    @endauth
                    @guest
                        {{ $post->user->title }}                    
                    @endguest
                        　發佈時間：{{ substr($post->passed_at,0,16)  }}
                </div>
                <div class="py-3 text-right">
                    <a class="btn btn-outline-primary mx-1" href="{{ route('posts.print',$post->id) }}" target="_blank">
                        <i class="fas fa-print"></i> 列印公告
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection