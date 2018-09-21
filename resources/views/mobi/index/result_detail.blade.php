@extends("mobi.include.mother")


@section("content")

    <div id="sub-main">
        <div class="com-article">
            <div class="head">
                <div class="title">
                    {{$page->title}}
                </div>
            </div>
            <div class="com-p">
                {!! htmlspecialchars_decode($page->body) !!}
            </div>
            <div style="height: 30px;"></div>
        </div>
    </div>


@endsection
