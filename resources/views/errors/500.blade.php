<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ trans('wck.company_name') }}</title>
    
    <!-- Bootstrap -->
    <link href="{{ asset("css/bootstrap.min.css") }}" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="{{ asset("css/font-awesome.min.css") }}" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="{{ asset("css/gentelella.min.css") }}" rel="stylesheet">
</head>

<body class="nav-md">
<div class="container body">
    <div class="main_container">
        <!-- page content -->
        <div class="col-md-12">
            <div class="col-middle">
                <div class="text-center">
                    <h1 class="error-number">500</h1>
                    <h2>系统发生了错误</h2>
                    <p>我们已经自动追踪该错误异常，若您仍然持续发生此错误，请联络我们。
                    </p>
                </div>
            </div>
        </div>
        <!-- /page content -->
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset("js/jquery.min.js") }}"></script>
<!-- Bootstrap -->
<script src="{{ asset("js/bootstrap.min.js") }}"></script>
<!-- Custom Theme Scripts -->
<script src="{{ asset("js/gentelella.min.js") }}"></script>
</body>
</html>