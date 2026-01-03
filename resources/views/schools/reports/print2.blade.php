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
                承辦人：{{ array_get($sections,$report_school->report->section_id) }} / {{ $report_school->report->user->name }}
                @if(!empty($report_school->report->user->telephone)) 
                    <small>TEL {{ $report_school->report->user->telephone }}</small> 
                @endif
            </div>
        </div>        
        <div class="row">
            <div class="col-12 text-start" style="font-size: 20px;">
                <?php
                    $y = substr($report_school->report->passed_at,0,4) - 1911;
                    $m = substr($report_school->report->passed_at,5,2);
                    $d = substr($report_school->report->passed_at,8,2);
                ?>
                <div>公告時間：中華民國{{ $y }}年{{ $m }}月{{ $d }}日 {{ substr($report_school->report->passed_at,11,5) }}</div>
                <div>填報編號：{{ $report_school->report_id }}</div>     
                <?php
                    $files = get_files(storage_path('app/public/report_files/' . $report_school->report_id));
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
                    @if($report_school->report->situation === 4)
                        <span style="color:red">[作廢]</span>
                        <strike>{{ $report_school->report->name }}</strike>
                    @else
                        {{ $report_school->report->name }}
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-start h2">
                說明：<br>
                <?php                
                    $content = str_replace(["說明：", "說明:"], "", $report_school->report->content);
                ?>
                <div class="h5 ms-3" style="word-break: break-word;">
                    @if($report_school->report->situation === 4)
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
                    @foreach($report_school->report->questions as $question)
                        <div class="form-group">
                            <label><strong>題目{{ $i }}：{{ $question->title }}</strong></label>
                            @if(isset($answer_data[$question->id]))
                                @if($report_school->signed_user_id == auth()->user()->id or $report_school->review_user_id == auth()->user()->id or check_a_user(auth()->user()->code,auth()->user()->id))
                                    <p>答：<span class="text-danger">{{ $answer_data[$question->id] }}</span></p> 
                                @else
                                    <p>答：****㊙️****</p>
                                @endif
                            @else
                                <p><span class="text-danger">尚未填</span></p>
                            @endif
                        </div>
                    <?php $i++; ?>
                    @endforeach
                    填報者：
                    @if(!empty($report_school->signed_user_id))
                        {{ userid2name($report_school->signed_user_id) }}
                    @endif
                    <br>
                    填報日期：{{ $report_school->signed_at }}
                    <br>
                    @if($report_school->review_user_id)
                        審核者：{{ $report_school->review_user->name }}
                        <br>
                        核可日期：{{ $report_school->updated_at }}
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