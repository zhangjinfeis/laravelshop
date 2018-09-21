@extends("mobi.include.mother")
@section('header')

@show



@section("content")

    <style>
        html,body,#js-map {
            width: 100%;
            height: 100%;
            margin: 0px;
        }
        .marker-my{width:16px; height: 16px; background:#007bff;border:#016bdd 1px solid;border-radius:8px;box-shadow:0 0 6px rgba(0,0,0,0.1);}
        .marker-my::after{content:'我的位置'; display:block; width:60px; height: 20px; line-height: 20px; background: #fff; position: absolute;left:-20px;top:-22px;border-radius:3px; background:#007bff;color:#fff; font-size: 12px; text-align: center; }

        /*省marker*/
        .marker-stadium {position:relative;height:30px;line-height: 30px;border-radius:4px; text-align: center;color: #fff;background:rgba(0,163,38,0.8);cursor: pointer; font-size: 14px;}
        .marker-stadium .arrow{ position: absolute;left:15px;bottom:-10px;width: 0;height: 0;border:5px solid transparent;border-bottom:5px solid rgba(0,163,38,0.8);transform:rotate(180deg);
            -ms-transform:rotate(180deg); 	/* IE 9 */
            -moz-transform:rotate(180deg); 	/* Firefox */
            -webkit-transform:rotate(180deg); /* Safari 和 Chrome */
            -o-transform:rotate(180deg); 	/* Opera */
        }
        .marker-stadium:hover{background:rgba(0,163,38,1);}
        .marker-stadium:hover .arrow{border-bottom:5px solid rgba(0,163,38,1);}
        .marker-stadium .name{min-width:40px;padding:0 0.8em; display: inline-block;white-space:nowrap; overflow:hidden; text-overflow:ellipsis;color:#fff;}

    </style>

    <script type="text/javascript" src="https://webapi.amap.com/maps?v=1.4.4&key=947dd69d7bf6cf026f8a4814d6d12cbd"></script>
    <!-- UI组件库 1.0 -->
    <script src="https://webapi.amap.com/ui/1.0/main.js?v=1.0.11"></script>

    <div class="m-jobmap-container" id="js-map"></div>
    <div class="m-jobmap-focus" onclick="return panToMy();"><span class="fa fa-dot-circle-o"></span></div>
    <div id="detail" class="d-none">
        <div class="m-stadium-map-alert js-detail">
            <div class="thumb"></div>

            <div class="form-group">
                <small class="form-text text-muted">场馆名称</small>
                <div class="name">场馆名称</div>
            </div>
            <div class="form-group">
                <small class="form-text text-muted">地址</small>
                <div class="address">地址</div>
            </div>
            <div class="form-group">
                <small class="form-text text-muted">电话</small>
                <div><i class="fa fa-phone-square" aria-hidden="true" style="color:#007bff"></i> <span class="phone">电话</span></div>
            </div>
            <div class="form-group">
                <small class="form-text text-muted">价格</small>
                <div class="price">价格</div>
            </div>
            <div class="form-group">
                <small class="form-text text-muted">场馆简介</small>
                <div class="body">价格<br>价格<br>价格<br>价格<br>价格<br>价格<br>价格<br>价格<br>价格<br>价格<br></div>
            </div>
        </div>

    <script>
        var map = new AMap.Map('js-map', {
            resizeEnable: true,
            zoom:11,
        })

        var bounds,timeout,cate_id="{{request('cate_id')}}",moveStatus = true,loading;
        map.on('complete', function() {
            bounds = map.getBounds();
            clearTimeout(timeout);
            //loading = $boot.loading({text:'加载中'});
            timeout = setTimeout(function(){
                ajax_markers();
            },50);
        });

        map.on('moveend', function() {
            if(!moveStatus) return false;
            moveStatus = false;
            bounds = map.getBounds();
            clearTimeout(timeout);
            //loading = $boot.loading({text:'加载中'});
            timeout = setTimeout(function(){
                ajax_markers();
            },50);
        });
        map.on('zoomend', function() {
            bounds = map.getBounds();
            clearTimeout(timeout);
            //loading = $boot.loading({text:'加载中'});
            timeout = setTimeout(function(){
                ajax_markers();
            },50);

        });

        //获取当前位置
        map.plugin('AMap.Geolocation', function() {
            var geolocation = new AMap.Geolocation({
                // 是否使用高精度定位，默认：true
                enableHighAccuracy: true,
                // 设置定位超时时间，默认：无穷大
                timeout: 10000,
                // 定位按钮的停靠位置的偏移量，默认：Pixel(10, 20)
                buttonOffset: new AMap.Pixel(10, 20),
                //  定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
                zoomToAccuracy: true,
                //  定位按钮的排放位置,  RB表示右下
                buttonPosition: 'RB'
            })

            geolocation.getCurrentPosition()
            AMap.event.addListener(geolocation, 'complete', onComplete)
            AMap.event.addListener(geolocation, 'error', onError)

            function onComplete (data) {
                if(data.position.getLng()){
                    //添加当前位置点
                    add_my_marker(data.position.getLng(),data.position.getLat());
                    //移动到当前位置对应的视窗
                    panToMy();
                }
            }

            function onError (data) {
                // 定位出错
            }
        });

        //地图移动至我的位置
        function panToMy(){
            map.panTo([my_position[0],my_position[1]]);
        }

        var markers = [],markers_my=[],my_position=[];
        //添加我当前的marker
        function add_my_marker(lng,lat){
            var marker = new AMap.Marker({ //添加自定义点标记
                map: map,
                position: [lng,lat], //基点位置
                offset: new AMap.Pixel(-8, -8), //相对于基点的偏移位置
                content: '<div class="marker-my"></div>'   //自定义点标记覆盖物内容
            });
            markers_my.push(marker);
            my_position = [lng,lat];
        }

        //获取当前视窗的点
        function ajax_markers(){
            var data = {};
            bounds.northeast.lng = bounds.northeast.lng<0 ? bounds.northeast.lng+360 : bounds.northeast.lng;
            data.lng_1 = bounds.southwest.lng;
            data.lng_2 = bounds.northeast.lng;
            data.lat_1 = bounds.southwest.lat;
            data.lat_2 = bounds.northeast.lat;
            data.cate_id = cate_id;
            $.ajax({
                type:'post',
                url:"{{url('/wx/stadium/ajax_get_markers')}}",
                data:data,
                success:function(res){
                    refresh_stadium_markers(res.data);
                    moveStatus = true;
                    //loading.close();
                }

            });
        }

        //渲染当前视窗的点
        function refresh_stadium_markers(data){
            remove_markers();
            for(i=0;i<data.length;i++){
                var content = '';
                content = '<div class="marker-stadium"><a class="name" title="'+data[i]['name']+'">'+data[i]['name']+'</a><span class="arrow"></span></div>';

                var marker = new AMap.Marker({ //添加自定义点标记
                    map: map,
                    position: [data[i]['lng'],data[i]['lat']], //基点位置
                    offset: new AMap.Pixel(-20, -38), //相对于基点的偏移位置
                    content: content   //自定义点标记覆盖物内容
                });
                marker.setExtData({
                    id: data[i]['id']
                });
                marker.on('click',function(e){
                    var param = e.target.getExtData()
                    alert_win(param.id);
                });
                markers.push(marker);
            }
        }

        //移除所有markers
        function remove_markers(){
            map.remove(markers);
            markers = [];
        }

        //弹出场馆详情
        function alert_win(id){
            $.ajax({
                type:'post',
                url:"{{url('/wx/stadium/ajax_get_stadium')}}",
                data:{id:id},
                success:function(res){
                    if(res.data.thumb){
                        $('.js-detail .thumb').html('').append("<img src='/image/"+res.data.thumb+"' />");
                    }
                    $('.js-detail .name').text(res.data.name);
                    $('.js-detail .address').text(res.data.address);
                    $('.js-detail .phone').html('<a href="tel:'+res.data.phone+'">'+res.data.phone+'</a>');
                    $('.js-detail .price').text(res.data.price);
                    $('.js-detail .body').html(res.data.body);
                }
            });
            setTimeout(function(){
                $boot.win({'id':"#detail",title:'场馆信息'});
            },200)
            return false;
        }



    </script>
@endsection

@section('footer')

@show