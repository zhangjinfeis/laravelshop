@extends("mobi.include.mother")


@section("content")
    <!-- page -->
        <!-- body -->
        <div id="sub-main">
            <div class="com-tabs">
                <a href="/mobi/about" class="item">公司简介</a>
                <a href="/mobi/zizhi" class="item">企业资质</a>
                <a href="/mobi/huanjing" class="item active">公司环境</a>
            </div>

            <div class="com-pic-list baguetteBox">
                @foreach($list as $vo)
                    <div class="item">
                        <a href="/image/{{$vo->thumb}}?a.jpg" data-caption="{{$vo->title}}" class="pic">
                            <img src="/image/{{$vo->thumb}}">
                        </a>
                        <div class="tit">{{$vo->title}}</div>
                    </div>
                @endforeach
            </div>
            {{$list->appends($_GET)->links()}}
        </div>
@endsection
