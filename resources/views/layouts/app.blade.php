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
        <link href=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css " rel="stylesheet">
        <link href=" https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css " rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/css/fontawesome.min.css" rel="stylesheet" />
        <link href=" https://cdn.jsdelivr.net/npm/venobox@2.1.8/dist/venobox.min.css " rel="stylesheet">
        <link href="{{ env('APP_URL') }}/css/my.css" rel="stylesheet" />
        @yield('my_css_file')        
        <script src=" https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/js/all.min.js "></script>
        <script src=" https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>        
        <script src=" https://cdn.jsdelivr.net/npm/venobox@2.1.8/dist/venobox.min.js "></script>
        <!-- Chosen v1.8.2 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.min.css" rel="stylesheet" />
        <link href="{{ asset('css/component-chosen.min.css') }}" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js"></script>
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
        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js "></script>        
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
    </body>
</html>