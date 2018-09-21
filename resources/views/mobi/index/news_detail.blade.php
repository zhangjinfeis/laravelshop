@extends("mobi.include.mother")


@section("content")
    <div id="sub-main">
        <div class="com-article">
            <div class="head">
                <div class="title">
                    {{$page->title}}
                </div>
                <div class="sub">
                    <span>发布时间：{{$page->created_at->format('Y-m-d')}}</span>
                </div>
            </div>
            <div class="com-p">
                {!! htmlspecialchars_decode($page->body) !!}
            </div>
            <div style="height: 30px;"></div>
        </div>
    </div>


@endsection
