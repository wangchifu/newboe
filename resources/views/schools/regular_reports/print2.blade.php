<html>
<head>
    <title>填報列印</title>
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
                <h1>彰化縣政府教育處 資料填報</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-end" style="font-size: 25px;">
                承辦人：{{ array_get($sections,$regular_report_school->regular_report->section_id) }} / {{ $regular_report_school->regular_report->user->name }}
                @if(!empty($regular_report_school->regular_report->user->telephone)) 
                    <small>TEL {{ $regular_report_school->report->user->telephone }}</small> 
                @endif
            </div>
        </div>        
        <div class="row">
            <div class="col-12 text-start" style="font-size: 20px;">
                <?php
                    $y = substr($regular_report_school->regular_report->passed_at,0,4) - 1911;
                    $m = substr($regular_report_school->regular_report->passed_at,5,2);
                    $d = substr($regular_report_school->regular_report->passed_at,8,2);
                ?>
                <div>公告時間：中華民國{{ $y }}年{{ $m }}月{{ $d }}日 {{ substr($regular_report_school->regular_report->passed_at,11,5) }}</div>
                <div>填報編號：{{ $regular_report_school->regular_report_id }}</div>     
                <?php
                    $files = get_files(storage_path('app/public/regular_report_files/' . $regular_report_school->regular_report_id));
                ?>                           
                @if(!empty($files))
                <div>附件：
                    @foreach($files as $file)
                        {{ $file }},
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
                    @if($regular_report_school->regular_report->situation === 4)
                        <span style="color:red">[作廢]</span>
                        <strike>{{ $regular_report_school->regular_report->semester }} {{ $regular_report_school->regular_report->regular_sample->name }}</strike>
                    @else
                        {{ $regular_report_school->regular_report->semester }} {{ $regular_report_school->regular_report->regular_sample->name }}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                說明：<br>
                <?php                
                    $content = str_replace(["說明：", "說明:"], "", $regular_report_school->regular_report->regular_sample->content);
                ?>
                <div class="h5 ms-3" style="word-break: break-word;">
                    @if($regular_report_school->regular_report->situation === 4)
                        <strike>{!! nl2br(strip_tags($content, "<ol><li><br>")) !!}</strike>
                    @else
                        {!! strip_tags(nl2br($content), "<ol><li><br>") !!}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                <h4>題目與填報</h4>
                <div class="h5 ms-3" style="word-break: break-word;">
                    @include('edus.regular_reports.sample_'.$sample_num)    
                    填報者：
                    @if(!empty($report_school->signed_user_id))
                        {{ userid2name($report_school->signed_user_id) }}
                    @endif
                    <br>
                    填報日期：{{ $regular_report_school->signed_at }}
                    <br>
                    @if($regular_report_school->review_user_id)
                        審核者：{{ $regular_report_school->review_user->name }}
                        <br>
                        核可日期：{{ $regular_report_school->updated_at }}
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