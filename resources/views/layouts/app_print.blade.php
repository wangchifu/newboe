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
    <body">
        @yield('content')                                
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