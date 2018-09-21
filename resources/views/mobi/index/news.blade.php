@extends("mobi.include.mother")


@section("content")


    <div id="sub-main">
        <div class="com-title">
            <span class="title">新闻中心</span>
        </div>
        <div class="com-news-list">
            @foreach($list as $vo)
            <a href="{{url('/mobi/news/detail'.'?id='.$vo->id)}}" class="item">
                <img src="/image/{{$vo->thumb}}" class="pic">
                <div class="cont">
                    <div class="tit of-1">{{$vo->title}}</div>
                    <div class="info of-2">
                        {{str_limit(strip_tags(htmlspecialchars_decode($vo->body)),170,'...')}}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        {{$list->appends($_GET)->links()}}
    </div>

@endsection
