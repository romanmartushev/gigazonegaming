<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Controller</title>
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.css">
    <link rel="stylesheet" href="/bower_components/font-awesome/css/font-awesome.css">
    <style type="text/css" href="">@yield('css')</style>
</head>
<body>
<div class="container" id="page-content">

    <div class="row">
        <div class="col-md-12">
            @yield('content')
        </div>
    </div>
</div>
<script type="text/javascript" src="/bower_components/jquery/dist/jquery.min.js"></script>
<script type="text/javascript" src="/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script type="text/javascript">@yield('js')</script>
</body>
</html>