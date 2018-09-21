@extends("mobi.include.mother")

@section("content")
    <!-- body -->
    <div id="sub-main">
        <div class="com-tabs">
            <a href="/mobi/about" class="item active">公司简介</a>
            <a href="/mobi/zizhi" class="item">企业资质</a>
            <a href="/mobi/huanjing" class="item">公司环境</a>
        </div>

        <div class="com-p">
            {!! htmlspecialchars_decode($page->body) !!}
        </div>
    </div>
@endsection
