<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>培奇智能运动</title>
    
    <!-- Bootstrap -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("css/font-awesome.min.css") }}" rel="stylesheet">
    <!-- NProgress -->
    <link href="{{ asset("css/nprogress.css") }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset("css/gentelella.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/bootstrap-datetimepicker.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/holdon.min.css") }}" rel="stylesheet">
    <link href="{{ asset("css/switchery.min.css") }}" rel="stylesheet">

    @stack('stylesheets')
</head>

<body class="login">
    @yield('main_container')

    <!-- jQuery -->
    <script src="{{ asset("js/jquery.min.js") }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset("js/bootstrap.min.js") }}"></script>
    <!-- Custom Theme Scripts -->
    <script src="{{ asset("js/gentelella.min.js") }}"></script>
    <script src="{{ asset("js/utility.js")}}"></script>
    <script src="{{ asset("js/moment.min.js")}}"></script>
    <script src="{{ asset("js/moment/zh-cn.js")}}"></script>
    <script src="{{ asset("js/bootstrap-datetimepicker.min.js")}}"></script>
    <script src="{{ asset("js/holdon.min.js")}}"></script>
    <script src="{{ asset("js/switchery.min.js")}}"></script>

    @stack('scripts')
</body>
</html>