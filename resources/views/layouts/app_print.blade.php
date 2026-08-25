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
        <script src="{{ env('APP_URL') }}/vendor/bootstrap/bootstrap.bundle.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/fontawesome/all.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/jquery/jquery.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/sweetalert2/sweetalert2.all.min.js"></script>
        <script src="{{ env('APP_URL') }}/vendor/venobox/venobox.min.js"></script>
        <!-- Chosen v1.8.2 -->
        <link href="{{ env('APP_URL') }}/vendor/chosen/chosen.min.css" rel="stylesheet" />
        <script src="{{ env('APP_URL') }}/vendor/chosen/chosen.jquery.min.js"></script>              
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