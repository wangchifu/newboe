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
        <link href="{{ env('APP_URL') }}/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <link href="{{ env('APP_URL') }}/fontawesome-free-6.7.2-web/css/all.css" rel="stylesheet" />
        <link href="{{ env('APP_URL') }}/css/my.css" rel="stylesheet" />
        @yield('my_css_file')
        <script src="{{ env('APP_URL') }}/js/jquery-3.7.1.js"></script>        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>                
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
        <script src="{{ env('APP_URL') }}/bootstrap-5.3.3-dist/js/bootstrap.bundle.js"></script>
        <!-- Core theme JS-->
        <script src="{{ env('APP_URL') }}/js/my.js"></script>
        @include('layouts.sweet_alert')
        @yield('my_js')
    </body>
</html>