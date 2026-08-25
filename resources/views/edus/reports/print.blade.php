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
                承辦人：{{ array_get($sections,$report->section_id) }} / {{ $report->user?->name }}
                @if(!empty($report->user?->telephone))
                    <small>TEL {{ $report->user?->telephone }}</small>
                @endif
            </div>
        </div>        
        <div class="row">
            <div class="col-12 text-start" style="font-size: 20px;">
                @if(!empty($report->passed_at))
                <?php
                    $y = substr($report->passed_at,0,4) - 1911;
                    $m = substr($report->passed_at,5,2);
                    $d = substr($report->passed_at,8,2);
                ?>
                <div>公告時間：中華民國{{ $y }}年{{ $m }}月{{ $d }}日 {{ substr($report->passed_at,11,5) }}</div>
                @else
                <div>公告時間：尚未通過</div>
                @endif
                <div>填報編號：{{ $report->id }}</div>     
                <?php
                    $files = get_files(storage_path('app/public/report_files/' . $report->id));
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
                    @if($report->situation === 4)
                        <span style="color:red">[作廢]</span>
                        <strike>{{ $report->name }}</strike>
                    @else
                        {{ $report->name }}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                說明：<br>
                <?php                
                    $content = str_replace(["說明：", "說明:"], "", $report->content);
                ?>
                <div class="h5 ms-3" style="word-break: break-word;">
                    @if($report->situation === 4)
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
                    <?php $i=1; ?>
                    @foreach($report->questions as $question)
                        <div class="form-group">
                            <label><strong>題目{{ $i }}：{{ $question->title }}</strong></label>                            
                        </div>
                    <?php $i++; ?>
                    @endforeach                        
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                <h4>對象學校</h4>
                <div class="h5 ms-3" style="word-break: break-word;">                                        
                    @foreach($schools as $school)
                        <span>{{ $school->school_name }}</span>,
                    @endforeach                
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