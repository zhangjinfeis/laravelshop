@extends("mobi.include.mother")


@section("content")
    <!-- page -->
        <!-- body -->
    <div id="sub-main">
        <div class="com-title">
            <span class="title">企业业绩</span>
        </div>
        <div class="com-case-list">
            @foreach($list as $vo)
            <a href="{{url('/mobi/result/detail').'?id='.$vo->id}}" class="item">
                <div class="tit of-1">{{$vo->title}}</div>
                @if(count($vo->thumbs_arr))
                <div class="pics">
                    @foreach($vo->thumbs_arr as $v)
                    <div class="pic-item">
                        <img src="/image/{{$v->md5}}">
                    </div>
                    @endforeach
                </div>
                @endif
            </a>
            @endforeach
        </div>
        {{$list->appends($_GET)->links()}}
    </div>
@endsection
