@extends('layouts.app_clean')

@section('title',$post->title)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm my-3">
                    <div class="card-head">
                        <img class="card-img-top img-responsive" src="{{ asset('images/posts'.$post->category_id.'.png') }}"
                            alt="{{ array_get($categories,$post->category_id) }}"
                            title="{{ array_get($categories,$post->category_id) }}">
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('posts.signedquickly',$post->id) }}" id="quickly_form" onsubmit="return false">
                            @csrf
                            {{ method_field('PATCH') }}
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
                                                {{ array_get($categories,$post->category_id) }} / <span style="color:red">[本公告已作廢]</span><strike>{{ $post->title }}</strike>
                                            @else
                                                [{{ array_get($categories,$post->category_id) }}] {{ $post->title }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-center post-th" width="20%">單位 / 發佈人</th>
                                        <td style="color: #000000">{{ array_get($sections,$post->section_id) }} / {{ $post->user?->name }}@if(!empty($post->user?->telephone)) <i class="fas fa-phone"></i> {{ $post->user?->telephone }}@endif</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-center post-th" width="20%">創建時間 / 點閱<br>發佈時間</th>
                                        <td style="color: #000000">{{ substr($post->created_at,0,16)  }} / {{ $post->views }}<br>{{ substr($post->passed_at,0,16) }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="text-center post-th" width="20%">內容</th>
                                        <td style="color: #000000">
                                            @if( $post->situation ===4 )
                                                <strike>{!! $post->content !!}</strike>
                                            @else
                                                {!! $post->content !!}
                                            @endif
                                        </td>
                                    </tr>
                                    @if(!empty($files))
                                    <tr>
                                        <th scope="row" class="text-center post-th" width="20%">附加檔案</th>
                                        <td>
                                            @foreach($files as $file)
                                                <a href="{{ route('posts.download',['id'=>$post->id,'filename'=>$file]) }}"
                                                title="點選下載附加檔案({{ $file }})" target="_blank">
                                                {{ $file }}
                                                </a><br>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                    @if(!empty($images))
                                    <tr>
                                        <th scope="row" class="text-center post-th" width="20%">相關照片</th>
                                        <td>
                                            @foreach($images as $image)
                                                <?php
                                                    $image_path = $images_path . '/' . $image;
                                                    $file_path = str_replace('/', '&', $image_path);
                                                ?>
                                                <a href="{{ asset('storage/post_photos/'.$post->id.'/'.$image) }}" target="_blank">
                                                <img src="{{ route('posts.img',$file_path) }}" height="100">
                                                </a>
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                    @if(!empty($post->url))
                                    <tr>
                                        <th scope="row" class="text-center post-th" width="20%">相關連結</th>
                                        <td>
                                            <a target="_blank" href="{{ $post->url }}" title="點選開啟相關連結網頁">
                                            {{ $post->url }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @if($post->category_id === 5)
                                        <tr>
                                            <th scope="row" class="text-center post-th" width="20%">發送對象學校</th>
                                            <td style="color: #000000">
                                                @foreach( $schools as $school)
                                                    {{ $loop->first ? '' : '、' }}
                                                    {{ $school->school_name }}
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-center post-th" width="20%">已簽收學校</th>
                                            <td style="color: #000000">
                                                @foreach( $signedSchools as $signedSchool)
                                                    {{ $loop->first ? '' : '、' }}
                                                    {{ $signedSchool->school->school_name }}({{ $signedSchool->user->name }})
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row" class="text-center post-th" width="20%">未簽收學校<br/>
                                                @if(count($noSignedSchools)>0)
                                                    @if(auth()->user()->id === $post->user_id)
                                                        @if($quick_signed)
                                                            <span class="text-danger">*已催促*</span>
                                                        @else
                                                            <input class="btn btn-danger btn-sm" type="submit" value="催簽收" onclick="sw_confirm2('確定要催促這些學校？','quickly_form')">
                                                        @endif
                                                    @endif
                                                @endif
                                            </th>
                                            <td style="color: #000000">
                                                @foreach( $noSignedSchools as $noSignedSchool)
                                                    {{ $loop->first ? '' : '、' }}
                                                    {{ $noSignedSchool->school_name }}
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <div>
                            {{ array_get($categories,$post->category_id) }}　{{ $post->user?->name }}
                        　 創建時間：{{ $post->created_at }}
                        </div>                        
                        <div class="py-3 text-right">
                            <button type="button" id="closeVeno" class="btn btn-secondary">
                                關閉視窗
                            </button>
                            <a class="btn btn-outline-primary mx-1" href="{{ route('posts.show_doing_post_print',$post->id) }}" target="_blank">
                                <i class="fas fa-print"></i> 列印公告
                            </a>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection