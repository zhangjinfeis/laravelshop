<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>

        @if(isset($page->title))
            <title>{{$page->title}}_{{$config['sitename']}}</title>
        @else
            <title>{{$config['sitename']}}</title>
        @endif


        @if(isset($page->keywords))
        <meta name="keywords" content="{{$page->keywords}}">
        @else
        <meta name="keywords" content="{{$config['keywords']}}">
        @endif


        @if(isset($page->description))
        <meta name="description" content="{{$page->description}}">
        @else
        <meta name="description" content="{{$config['description']}}">
        @endif

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="format-detection" content="telephone=no, email=no">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta name="apple-mobile-web-app-title" content="应用标题">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <meta http-equiv="Cache-Control" content="no-transform">
    <!-- uc强制竖屏 --><meta name="screen-orientation" content="portrait">
    <!-- UC强制全屏 --> <meta name="full-screen" content="yes">
    <!-- UC应用模式 --> <meta name="browsermode" content="application">
    <!-- QQ强制竖屏 --><meta name="x5-orientation" content="portrait">
    <!-- QQ强制全屏 --><meta name="x5-fullscreen" content="true">
    <!-- QQ应用模式 --><meta name="x5-page-mode" content="app">



    @section('cssjs')
        @include('mobi.include.cssjs')
        {{--ajax请求csrf--}}
        <script>
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
            });
        </script>
    @show
</head>
<body>
@section('header')
    @include('mobi.include.header')
@show

<div class="wrap">
@yield("content")
</div>

@section('footer')
    @include('mobi.include.footer')
    <script src="/resources/mobi/js/my.js"></script>
@show
</body>
</html>