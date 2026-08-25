<html>
<head>
    <title>公告列印</title>
    <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css " rel="stylesheet">
    <style type="text/css">
        @import url(https://fonts.googleapis.com/earlyaccess/cwtexkai.css);
        body {
            font-family: 'cwTeXKai', serif;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center fw-bold">
                <h1>彰化縣政府教育處 {{ $categories[$post->category_id] ?? '' }}</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end" style="font-size: 25px;">
                承辦人：{{ array_get($sections,$post->section_id) }} / 
                @auth
                    {{ $post->user?->name }}
                @endauth
                @guest
                    {{ $post->user?->title }}                    
                @endguest                
                @if(!empty($post->user?->telephone)) 
                    <small>TEL {{ $post->user?->telephone }}</small> 
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                受文者：
                @if($post->category_id == "5" and $post->another != 1)
                    @auth
                        @if(!empty(auth()->user()->school))
                            彰化{{ auth()->user()->school }}
                        @endif
                    @endauth
                @else
                    全體國民
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start" style="font-size: 20px;">
                <?php
                    $y = substr($post->passed_at,0,4) - 1911;
                    $m = substr($post->passed_at,5,2);
                    $d = substr($post->passed_at,8,2);
                ?>
                <div>公告時間：中華民國{{ $y }}年{{ $m }}月{{ $d }}日 {{ substr($post->passed_at,11,5) }}</div>
                <div>公告編號：{{ $post->post_no }}</div>
                <div>速別：
                    @if($post->type === 1)
                        最速件
                    @else
                        普通件
                    @endif
                </div>
                @if(!empty($files))
                <div>附件：
                    @foreach($files as $file)
                        {{ $file }},
                    @endforeach
                </div>
                @endif
                @if(!empty($post->url))
                <div>相關連結：{{ $post->url }}</div>
                @endif
                @if(!empty($images))
                <div>相關照片：
                    @foreach($images as $image)
                        {{ $image }},
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 d-flex">
                <div class="pe-2 fw-bold h2" style="white-space: nowrap; vertical-align: top;">
                    主旨：
                </div>
                <div class="flex-grow-1 h2">
                    @if($post->situation === 4)
                        <span style="color:red">[作廢]</span>
                        <strike>[{{ array_get($categories,$post->category_id) }}] {{ $post->title }}</strike>
                    @else
                        {{ $post->title }}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                說明：<br>
                <?php
                    $content = str_replace(["說明：", "說明:"], "", $post->content);
                ?>
                <div class="h5 ms-3" style="word-break: break-word;">
                    @if($post->situation === 4)
                        <strike>{!! nl2br(strip_tags($content, "<ol><li><br>")) !!}</strike>
                    @else
                        {!! strip_tags(nl2br($content), "<ol><li><br>") !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
<script>
        // window.onload 會等待 HTML、所有圖片、CSS 都下載完畢才執行
        window.onload = function() {
            // 稍微延遲 200ms 可以確保某些瀏覽器的渲染引擎完全就緒
            setTimeout(function() {
                window.print();
            }, 200);
        };
    </script>
</html>