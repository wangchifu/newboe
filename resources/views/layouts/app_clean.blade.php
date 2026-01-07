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
        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js "></script>
        <script src=" https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.7.2/js/all.min.js "></script>
        <script src=" https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js "></script>        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>        
        <script src=" https://cdn.jsdelivr.net/npm/venobox@2.1.8/dist/venobox.min.js "></script>     
        <!-- Chosen v1.8.2 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.min.css" rel="stylesheet" />
        <link href="{{ asset('css/component-chosen.min.css') }}" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js"></script>              
        @yield('my_js_file')
    </head>
    <body>
        @yield('my_css')
        <!-- Responsive navbar-->        
        <!-- Page header with logo and tagline-->   
        @yield('header')             
        <!-- Page content-->
        <div class="container">
            <div class="row">                
                <!-- Blog entries-->
                @yield('content')
            </div>
        </div>
        <!-- Footer-->        
        <!-- Bootstrap core JS-->
        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js "></script>
        <!-- Core theme JS-->
        <script src="{{ env('APP_URL') }}/js/my.js"></script>
        @include('layouts.sweet_alert')
        @yield('my_js')
<script>
    var vb = new VenoBox({
        selector: '.venobox',
        numeration: true,
        infinigall: true,
        //share: ['facebook', 'twitter', 'linkedin', 'pinterest', 'download'],
        spinner: 'rotating-plane'
    });

    $(document).on('click', '.vbox-close', function() {
        vb.close();
    });
    
    document.getElementById('closeVeno').addEventListener('click', function() {
    // 檢查父視窗是否有 VenoBox 實例並執行關閉
    if (window.parent && window.parent.bootstrap) {
        // 針對 VenoBox 2.x 版本
        // 如果你在主頁面定義變數為 myVeno = new VenoBox();
        // 則可以使用 window.parent.myVeno.close();
        
        // 通用作法：模擬按下 VenoBox 的關閉鈕或觸發關閉事件
        parent.document.querySelector('.vbox-close').click();
    } else {
        // 另一種方式：直接透過 VenoBox 的 API (前提是主頁面有宣告)
        window.parent.jQuery.venobox().trigger('click'); // 舊版適用
    }
  });
</script>        
    </body>
</html>