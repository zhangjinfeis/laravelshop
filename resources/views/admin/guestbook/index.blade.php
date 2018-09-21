@extends("admin.include.mother")

@section("content")

    <div class="u-breadcrumb">
        <a class="back" href="javascript:window.location.reload();" ><span class="fa fa-repeat"></span> 刷新</a>
        <span class="name">留言</span>
    </div>
    <div class="h20"></div>

    <table class="table table-hover">

            <tr>
                <th>ID</th>
                <th>留言者称呼</th>
                <th>电话</th>
                <th>email</th>
                <th>留言内容</th>
                <th>留言时间</th>
            </tr>

        @foreach($list as $vo)
            <tr>
                <td>{{$vo->id}}</td>
                <td>
                    {{$vo->name}}
                </td>
                <td>
                    {{$vo->phone}}
                </td>
                <td>
                    {{$vo->email}}
                </td>
                <td>
                    {{$vo->body}}
                </td>
                <td>
                    {{$vo->created_at->format('Y-m-d H:i')}}

                </td>
            </tr>
        @endforeach

    </table>
    {{$list->links()}}
@endsection