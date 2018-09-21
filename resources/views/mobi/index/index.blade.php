@extends("mobi.include.mother")

@section("content")
    <!-- body -->
    <div id="main">
        <div id="slide">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                    @foreach($banner as $vo)
                        <a target="{{$vo->target}}" href="{{$vo->url}}" class="swiper-slide" style="background-image:url('/image/{{$vo->thumb}}');"></a>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!-- 公司简介 -->
        <div class="index-wrap">
            <div class="com-title">
                <span class="title">公司简介</span>
                <a href="{{url('/mobi/about')}}" class="more">更多</a>
            </div>
            <div class="inner">
                <div class="com-p">
                    {{str_limit(strip_tags(htmlspecialchars_decode($about->body)),270,'...')}}
                </div>
            </div>
        </div>
        <!-- 企业业绩 -->
        <div class="index-wrap">
            <div class="com-title">
                <span class="title">企业业绩</span>
                <a href="{{url('/mobi/result')}}" class="more">更多</a>
            </div>
            <div class="inner">
                <div class="com-case-list">
                    @foreach($result as $vo)
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
            </div>
        </div>

        <!-- 新闻中心 -->
        <div class="index-wrap">
            <div class="com-title">
                <span class="title">新闻中心</span>
                <a href="{{url('/mobi/news')}}" class="more">更多</a>
            </div>
            <div class="inner">
                <div class="com-news-list">
                    @foreach($news as $vo)
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
            </div>
        </div>

        <!-- 联系我们 -->
        <div class="index-wrap">
            <div class="com-title">
                <span class="title">联系我们</span>
                <a href="{{url('/mobi/contact')}}" class="more">更多</a>
            </div>
            <div class="inner">
                @include('mobi.include.map')

                <div class="com-address">
                    <div class="com-p">
                        {!! htmlspecialchars_decode($contact->body) !!}
                    </div>
                </div>
                <a href="tel:{{$config['phone1']}}" class="com-tel-btn">
                    <i class="icon"></i>
                    {{$config['phone1']}}
                </a>
            </div>
        </div>

    </div>
@endsection
