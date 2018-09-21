<!-- header -->
<div id="header">
    <a href="/mobi" class="logo"><img src="/resources/mobi/images/logo.png" alt=""></a>
    <div id="hamburger" class="">
        <i class="icon-bar"></i>
        <i class="icon-bar"></i>
        <i class="icon-bar"></i>
    </div>
</div>
<!-- menu -->
<div id="menu">
    <ul class="menu-nav">
        @foreach($menu1 as $vo)
        <li class="item"><a target="{{$vo['target']}}" href="{{$vo['url']}}">{{$vo['name']}}</a></li>
        @endforeach
    </ul>
</div>