<!DOCTYPE html>
<html lang="zh-TW">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
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
        @yield('my_js_file')
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
        <!-- Footer-->
        @include('layouts.footer')
        <!-- Bootstrap core JS-->
        <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js "></script>
        <!-- Core theme JS-->
        <script src="{{ env('APP_URL') }}/js/my.js"></script>
        @include('layouts.sweet_alert')
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
        </script>
        @yield('my_js')
    </body>
</html>