<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta http-equiv="Content-Security-Policy" content="script-src * 'unsafe-inline' 'unsafe-eval';">
        @yield('my_meta')
        <title>@yield('title')-新雲端</title>
        <!-- Favicon-->
        <link rel="icon" type="image/x-icon" href="{{ asset('images/sun.png') }}" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="{{ env('APP_URL') }}/vendor/bootstrap/bootstrap.min.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}/vendor/bootstrap-icons/bootstrap-icons.min.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}/vendor/fontawesome/fontawesome.min.css" rel="stylesheet" />
        <link href="{{ env('APP_URL') }}/vendor/venobox/venobox.min.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}/css/my.css" rel="stylesheet" />
        @yield('my_css_file')
        <script src="{{ env('APP_URL') }}/vendor/fontawesome/all.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/jquery/jquery.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/sweetalert2/sweetalert2.all.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/venobox/venobox.min.js"></script>
        <!-- Chosen v1.8.2 -->
        <link href="{{ env('APP_URL') }}/vendor/chosen/chosen.min.css" rel="stylesheet" />
        <script src="{{ env('APP_URL') }}/vendor/chosen/chosen.jquery.min.js"></script>
        @yield('my_js_file')

        <!-- Google tag (gtag.js) Google Analytics-->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-RPLBGVYQZ6"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-RPLBGVYQZ6');
        </script>
    </head>
    <body>
        @yield('my_css')
        <!-- 回到頂部按鈕 -->
        <button id="backToTop">
            <svg viewBox="0 0 24 24">
                <path d="M5 15l7-7 7 7"></path>
            </svg>
        </button>
        <!-- Responsive navbar-->
        @include('layouts.nav')
        <!-- Page header with logo and tagline-->
        @yield('header')
        <!-- Page content-->
        <div class="container">
            <div class="row">                                                     
                <!-- Blog entries-->
                @yield('content')
            </div>
        </div>
        @auth
        <?php 
            if (!session('user_read_ids')) {
            $user_read_ids = \App\Models\UserRead::where('user_id',auth()->user()->id)->pluck('system_post_id')->toArray();    
            session(['user_read_ids' => $user_read_ids]);
            }
            
            $system_posts = [];
            if(session('user_all_read') != 1){      
            $system_posts = \App\Models\SystemPost::whereNotin('id',session('user_read_ids'))
            ->where('start_date','<=',date('Y-m-d'))
            ->where('end_date','>=',date('Y-m-d'))
            ->get();
            if(count($system_posts)==0){
                session(['user_all_read' => 1]);
            }
            }

        ?>
        @if(count($system_posts)>0)
            <script type="text/javascript">
            window.onload = function () {
                $("#simpleModal").modal('show');
            };      
            </script>
            <!-- Modal -->
            <div class="modal fade" id="simpleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">系統公告</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <?php $no_read_sp=''; ?>
                <div class="modal-body">
                    <?php $i=1; ?>
                    @foreach($system_posts as $system_post)   
                    <?php              
                    $no_read_sp .= $system_post->id.',';
                    ?>         
                    公告{{ $system_post->id }}：<br>
                    {!! nl2br($system_post->content) !!}
                    @if(count($system_posts)>1 and $i != count($system_posts))
                    <hr>
                    @endif
                    <?php $i++; ?>            
                    @endforeach            
                    <?php $no_read_sp = substr($no_read_sp,0,-1); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" onclick="window.location='{{ route('user_reads',$no_read_sp) }}'">知道了</button>            
                </div>
                </div>
            </div>
            </div>  
        @endif
        @endauth        
        <!-- Footer-->
        @include('layouts.footer')
        <!-- Bootstrap core JS-->
        <script src="{{ env('APP_URL') }}/vendor/bootstrap/bootstrap.bundle.min.js"></script>
        <!-- Core theme JS-->
        <script src="{{ env('APP_URL') }}/js/my.js"></script>
        @include('layouts.sweet_alert')
        @auth
            @include('layouts.system_modal')
        @endauth
        <script>
            var vb = new VenoBox({
                selector: '.venobox',                
                numeration: true,
                infinigall: true,
                //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
                spinner: 'rotating-plane',
                maxWidth: '100%',
                maxHeight: '90%',
                onPostOpen: function(el, type, item, data){
                // 找到 iframe 並讓它取得焦點
                const iframe = document.querySelector('.vbox-content iframe');
                if (iframe) {
                    iframe.focus();
                }
            }
            });
        
            $(document).on('click', '.vbox-close', function() {
                vb.close();
            });
        </script>
        @yield('my_js')
        <style>
            .vbox-content {
            position: relative !important;
            }

            .vbox-content iframe,
            .vbox-content video {
            position: absolute !important;
            top: 50% !important;
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            height: 90vh !important;
            width: 100% !important;
            object-fit: contain;
            }

            @media (max-width: 768px) {
            .vbox-content iframe,
            .vbox-content video {
                height: 90vh !important;
            }
            }
        </style>

        </style>

        <div id="helper-widget" style="position:fixed;bottom:20px;right:20px;z-index:9999;">
            <div id="helper-menu" style="display:none;background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,0.15);padding:12px;margin-bottom:10px;min-width:200px;">
                <div style="font-weight:bold;margin-bottom:8px;padding-bottom:6px;border-bottom:1px solid #eee;">
                    <i class="fas fa-headset"></i> 小幫手
                </div>
                <a href="{{ route('qanda') }}" class="d-block text-decoration-none text-dark py-2 px-2 rounded helper-item">
                    <i class="fas fa-question-circle text-primary"></i> 常見問題
                </a>
                @auth
                <a href="{{ route('wrench.index') }}" class="d-block text-decoration-none text-dark py-2 px-2 rounded helper-item">
                    <i class="fas fa-comment-dots text-success"></i> 系統報錯與建議
                </a>
                @if(auth()->user()->group_id==1 && !check_b_user(auth()->user()->code, auth()->user()->id))
                <a href="#!" onclick="showPowerInfo()" class="d-block text-decoration-none text-dark py-2 px-2 rounded helper-item">
                    <i class="fas fa-user-shield text-warning"></i> 我要簽收/填報權限
                </a>
                @endif
                @endauth
            </div>
            <button onclick="toggleHelperMenu()" style="width:54px;height:54px;border-radius:50%;border:none;background:linear-gradient(135deg,#ffc107,#ff9800);color:#fff;font-size:24px;cursor:pointer;box-shadow:0 4px 14px rgba(255,152,0,0.4);transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                <i class="fas fa-lightbulb"></i>
            </button>
        </div>
        <script>
        function toggleHelperMenu(){
            var m=document.getElementById('helper-menu');
            m.style.display=m.style.display==='none'?'block':'none';
        }
        document.addEventListener('click',function(e){
            var w=document.getElementById('helper-widget');
            if(w && !w.contains(e.target)){
                document.getElementById('helper-menu').style.display='none';
            }
        });
        @auth
        @if(auth()->user()->group_id==1 && !check_b_user(auth()->user()->code, auth()->user()->id))
        function showPowerInfo(){
            @php
                $widgetUserIds = \App\Models\UserPower::where('section_id', auth()->user()->code)
                    ->where('power_type', 'A')
                    ->pluck('user_id');
                $widgetUsers = \App\Models\User::whereNull('disable')->whereIn('id', $widgetUserIds)->get();
                $widgetAdmins = "請你找以下管理者給你適當權限：<br>";
                foreach($widgetUsers as $wu){
                    $widgetAdmins .= $wu->title." ".$wu->name."<br>";
                }
            @endphp
            sw_alert('{!! $widgetAdmins !!}');
            document.getElementById('helper-menu').style.display='none';
        }
        @endif
        @endauth
        </script>
        <style>
        .helper-item:hover{background:#f0f0f0;}

    </body>
</html>