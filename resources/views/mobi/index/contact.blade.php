@extends("mobi.include.mother")


@section("content")
    <div id="sub-main">
        <div class="com-title">
            <span class="title">联系我们</span>
        </div>

        @include('mobi.include.map')



        <div class="com-address">
            <div class="com-p">
                {!! htmlspecialchars_decode($page->body) !!}
            </div>

        </div>
        <a href="tel:{{$config['phone1']}}" class="com-tel-btn">
            <i class="icon"></i>
            {{$config['phone1']}}
        </a>
    </div>


@endsection
