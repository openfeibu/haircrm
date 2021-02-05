<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ route('home') }}">主页</a><span lay-separator="">/</span>
            <a><cite>公司信息管理</cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('setting/updateCompany')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">公司名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="company_name" lay-verify="companyName" autocomplete="off" placeholder="请输入公司名称" class="layui-input" value="{{$company['company_name']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">地址</label>
                        <div class="layui-input-inline">
                            <input type="text" name="address" lay-verify="address" autocomplete="off" placeholder="请输入地址" class="layui-input" value="{{$company['address']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">电话</label>
                        <div class="layui-input-inline">
                            <input type="text" name="tel" lay-verify="tel" autocomplete="off" placeholder="请输入电话" class="layui-input" value="{{$company['tel']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" lay-verify="email" autocomplete="off" placeholder="请输入邮箱" class="layui-input" value="{{$company['email']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">QQ</label>
                        <div class="layui-input-inline">
                            <input type="text" name="qq" lay-verify="qq" autocomplete="off" placeholder="请输入QQ" class="layui-input" value="{{$company['qq']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">经纬度</label>
                        <div class="layui-input-inline">
                            <input type="text" name="longitude" lay-verify="longitude" autocomplete="off" placeholder="请输入经度" class="layui-input" value="{{$company['longitude']}}">
                        </div>
                        <div class="layui-input-inline">
                            <input type="text" name="latitude" lay-verify="latitude" autocomplete="off" placeholder="请输入纬度" class="layui-input" value="{{$company['latitude']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">获取点位</label>
                        <div class="layui-input-inline">
                            <input id="keyword" type="textbox"  class="layui-input"  value="">
                            <input type="button" value="搜索" class="layui-button-mapsearch"  onclick="searchKeyword()">
                            <div class="layui-form-mid layui-word-aux">点击地图快速获取经纬度</div>
                        </div>

                        <div id="map"></div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key={{ config('common.qq_map_key') }}"></script>
{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>
    var ue = getUe();
    window.onload = function(){
        init();
    }
    layui.use('laydate', function() {
        var laydate = layui.laydate;
        laydate.render({
            elem: '#business_time'
            ,type:'time'
            ,format:'HH:mm'
            , range: true
        });
    });
</script>
<script>
    var geocoder,map,markers = [];
    var init = function() {
        var center = new qq.maps.LatLng(23.15641,113.3318);
        map = new qq.maps.Map(document.getElementById('map'),{
            center: center,
            zoom: 15
        });

        //调用Poi检索类
        geocoder = new qq.maps.Geocoder({

            complete : function(result){
                console.log(result)
                map.setCenter(result.detail.location);
                var marker = new qq.maps.Marker({
                    map:map,
                    position: result.detail.location
                });
                markers.push(marker)
                document.getElementsByName('longitude')[0].value = result.detail.location.lng;
                document.getElementsByName('latitude')[0].value = result.detail.location.lat;

                qq.maps.event.addListener(marker,'click',function(event) {
                    document.getElementsByName('longitude')[0].value = event.latLng.getLng();
                    document.getElementsByName('latitude')[0].value = event.latLng.getLat();
                })


            },
            //若服务请求失败，则运行以下函数
            error: function() {
                alert("无法获取地址，请检查地址是否正确");
            }
        });
        qq.maps.event.addListener(map,'click',function(event) {
            document.getElementsByName('longitude')[0].value = event.latLng.getLng();
            document.getElementsByName('latitude')[0].value = event.latLng.getLat();
        })
    }
    //清除地图上的marker
    function clearOverlays(overlays) {
        var overlay;
        while (overlay = overlays.pop()) {
            overlay.setMap(null);
        }
    }
    //调用poi类信接口
    function searchKeyword() {
        var keyword = document.getElementById("keyword").value;
        //region = new qq.maps.LatLng(39.936273,116.44004334);
        clearOverlays(markers);

        // searchService.setPageCapacity(5);
        geocoder.getLocation(keyword);//根据中心点坐标、半径和关键字进行周边检索。

    }
</script>