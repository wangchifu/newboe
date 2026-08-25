<html>
<head>
    <title>
        公告列印1
    </title>
</head>
<body>
<style>
        table {
            border: 2px solid ; border-collapse: collapse;
            margin: 5px;
            }
        tr,th,td {
                border: 2px solid ;
            }
        th,td{
                padding: 1px;
                font-size:14px;
            }
</style>
    <h1 style="align-content: center">彰化縣教育處新雲端
        @if($post->post_no)
            [{{ $post->post_no }}]
        @endif
        {{ $categories[$post->category_id] ?? '' }}
    </h1>
    <table border="1">
        <tbody>
        <tr>
            <td width="120">標題</td>
            <td>
                <h3>
                    @if($post->type===1)
                        <span class="text-danger">
                                        [最速件]
                                    </span>
                    @endif
                    @if( $post->situation ===4 )
                        <span style="color:red">[公告作廢]</span> <strike>[{{ array_get($categories,$post->category_id) }}] {{ $post->title }}</strike>
                    @else
                        {{ $post->title }}
                    @endif
                </h3>
            </td>
        </tr>
        <tr>
            <td>單位 / 發佈人</td>
            <td>{{ array_get($sections,$post->section_id) }} / {{ $post->user?->name }}@if(!empty($post->user?->telephone)) / TEL {{ $post->user?->telephone }}@endif</td>
        </tr>
        <tr>
            <td>時間 / 點閱</td>
            <td>{{ substr($post->created_at,0,16)  }} / {{ $post->views }} </td>
        </tr>
        <tr>
            <td colspan="2">公告內容</td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 18px">
                <?php $content = $post->content; ?>
                @if( $post->situation ===4 )
                    <strike>{!! nl2br(strip_tags($content,"<ol><li><br>")) !!}</strike>
                @else
                    {!! strip_tags(nl2br($content),'<ol><li><br>') !!}
                @endif
            </td>
        </tr>
        @if(!empty($files))
            <tr>
                <td>
                    附加檔案
                </td>
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
                <td>
                    相關照片
                </td>
                <td>
                    @foreach($images as $image)
                        <?php
                        $image_path = $images_path . '/' . $image;
                        $file_path = str_replace('/', '&', $image_path);
                        ?>
                        <a href="{{ asset('storage/post_photos/'.$post->id.'/'.$image) }}" target="_blank">
                            <img src="{{ route('posts.img',$file_path) }}" width="100">
                        </a>
                    @endforeach

                </td>
            </tr>
        @endif

        @if(!empty($post->url))
            <tr>
                <td>
                    相關連結
                </td>
                <td>
                    <a target="_blank" href="{{ $post->url }}" title="點選開啟相關連結網頁">
                        {{ $post->url }}
                    </a>
                </td>
            </tr>
        @endif

        @if($post->category_id === 5)
            <tr>
                <td>發送對象學校</td>
                <td>
                    @foreach( $schools as $school)
                        {{ $loop->first ? '' : '、' }}
                        {{ $school->school_name }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>
                    已簽收學校
                </td>
                <td>
                    @foreach( $signedSchools as $signedSchool)
                        {{ $loop->first ? '' : '、' }}
                        {{ $signedSchool->school->school_name }}({{ $signedSchool->user->name }})
                    @endforeach
                </td>
            </tr>
            <tr>
                <td>
                    未簽收學校
                </td>
                <td>
                    @foreach( $noSignedSchools as $noSignedSchool)
                        {{ $loop->first ? '' : '、' }}
                        {{ $noSignedSchool->school_name }}
                    @endforeach
                </td>
            </tr>
        @endif

        </tbody>
    </table>
<script>
        // window.onload 會等待 HTML、所有圖片、CSS 都下載完畢才執行
        window.onload = function() {
            // 稍微延遲 200ms 可以確保某些瀏覽器的渲染引擎完全就緒
            setTimeout(function() {
                window.print();
            }, 200);
        };
</script>
</body>
</html>