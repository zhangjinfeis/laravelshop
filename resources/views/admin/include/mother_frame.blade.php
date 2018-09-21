<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{$config['sitename']}}</title>
    <meta name="renderer" content="webkit">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @section('cssjs')
        <script src="/resources/plugs/jquery/jquery-3.3.1.min.js" ></script>
        {{--bootstrap组件--}}
        <link rel="stylesheet" href="/resources/plugs/bootstrap/bootstrap.min.css" >
        <script src="/resources/plugs/bootstrap/popper.min.js"></script>
        <script src="/resources/plugs/bootstrap/bootstrap.min.js"></script>

        {{--awesome字体--}}
        <link href="/resources/plugs/awesome/font/css/font-awesome.min.css" rel="stylesheet">

        {{--bootstrap-dialog--}}
        <script src="/resources/plugs/bootstrap/bootstrap.dialog.js"></script>

        {{--样式--}}
        <link rel="stylesheet" href="/resources/admin/css/frame.css">
        {{--ajax请求csrf--}}
        <script>
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
        </script>
    @show
</head>
<body>

@yield("content")





</body>
</html>