<style>
    .layui-table-cell{height:80px;}
    .layui-table-header .layui-table-cell, .layui-table-tool-panel li{white-space: pre-wrap !important;}
</style>
<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('onbuy/order/index') }}"><cite>Onbuy 产品出单量</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">

                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">下单日期 *</label>
                    <div class="layui-input-block">
                        <input type="text" name="date" id="date" class="layui-input search_key">
                    </div>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="onbuy_order_products.sku" id="demoReload" placeholder="sku" autocomplete="off">
                </div>
                <div class="layui-inline">

                    <input class="layui-input search_key" name="onbuy_order_products.name" id="demoReload" placeholder="名称" autocomplete="off">
                </div>

                <div class="layui-inline">
                    <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
                </div>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <p>
    <a class="layui-btn layui-btn-warm layui-btn-sm" lay-event="in_inventory">进货</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="out_inventory">出货</a>
    </p>
    <p>
    <a class="layui-btn layui-btn-normal layui-btn-sm" href="{{ guard_url('onbuy/order/') }}?search[onbuy_order_products.sku]=@{{ d.sku }}" target="_blank">查询订单</a>
    </p>
</script>
<script type="text/html" id="imageTEM">
    <a href="@{{ d.product_url }}" target="_blank"><img src="@{{d.image_urls.thumb}}" alt="" height="58"></a>
</script>
<script type="text/html" id="productTEM">
    <div>
        @{{# if(parseInt(d.need_out) > 0){ }}
        <p ><a href="@{{ d.product_url }}" target="_blank" style="color:red">@{{ d.name }}</a></p>
        @{{# }else{  }}
        <p ><a href="@{{ d.product_url }}" target="_blank" >@{{ d.name }}</a></p>
        @{{# }  }}

        <p> sku: @{{ d.sku }} </p>
    </div>
</script>
<script type="text/html" id="needPurchaseTEM">
    <div>
        @{{# if(parseInt(d.need_purchase) >= 0){ }}
        @{{ d.need_purchase }}
        @{{# }else{  }}
        <span style="color:red">@{{ d.need_purchase }}</span>
        @{{# }  }}
    </div>
</script>
<script>
    var main_url = "{{guard_url('onbuy/order_products')}}";
    var delete_all_url = "{{guard_url('onbuy/order/destroyAll')}}";

    layui.use(['jquery','element','table','laydate'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate= layui.laydate;

        $(document).on('mouseenter', '.analyseTips', function ()
        {
            var tp= $(this).attr("lay-tips");
            this.index = layer.tips('<div style="padding: 5px; font-size: 12px; color: #eee;">' + tp+ '</div>', this, {
                time: -1
                , maxWidth: 260
                , tips: [3, '#3A3D49']
            });
        }).on('mouseout','.analyseTips', function () {
            layer.close(this.index);
        });

        table.render({
            elem: '#fb-table'
            ,id:'fb-table'
            ,url: main_url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'image',title:'图片', width:120,templet:'#imageTEM',height:48}
                ,{field:'name',title:'{{ trans('goods.name') }}',width:250,templet:'#productTEM'}
                ,{field:'purchase_url',title:'采购链接', width:180, edit:'text'}
                ,{field:'purchase_price',title:'采购价',width:90}
                ,{field:'total_quantity',title:'销售量',width:90, fixed: 'right' ,sort:true}
                ,{field:'inventory',title:'库存',width:80, edit:'text', fixed: 'right',sort:true}
                ,{field:'out_inventory',title:'总出货',width:90,  edit:'text', fixed: 'right',sort:true}
                ,{field:'need_purchase',title:'需拿货',width:90, fixed: 'right',templet:'#needPurchaseTEM',sort:true}//需拿货 = 销售量 - 库存 - 总出货
                ,{field:'score',title:'{{ trans('app.actions') }}', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ $limit }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,done:function (res, curr, count) {
                element.init();
            }
        });
        //监听工具条
        //监听工具条
        table.on('tool(fb-table)', function(obj){
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/'+data.id,
                        data : data,
                        type : 'delete',
                        success : function (data) {
                            obj.del();
                            layer.close(load);
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            layer.msg('服务器出错');
                        }
                    });
                });
            } else if(obj.event === 'edit'){
                window.location.href=main_url+'/'+data.id
            }else if(obj.event === 'in_inventory'){
                layer.prompt({title: '输入进货数量，并确认', formType: 0}, function(number, index){
                    var data = obj.data;
                    var ajax_data = {};
                    ajax_data['_token'] = "{!! csrf_token() !!}";
                    ajax_data['inventory'] = parseInt(data.inventory) + parseInt(number);
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url :  "{{guard_url('onbuy/listing')}}"+'/'+data.product_id,
                        data : ajax_data,
                        type : 'PUT',
                        success : function (data) {
                            layer.close(load);
                            if(data.code == 0)
                            {
                                var nPage = $(".layui-laypage-curr em").eq(1).text();
                                //执行重载
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }else{
                                layer.msg(data.msg);
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            layer.msg('服务器出错');
                        }
                    });
                });
            } else if(obj.event === 'out_inventory'){
                layer.prompt({title: '输入退货数量，并确认', formType: 0}, function(number, index){


                    var data = obj.data;
                    var ajax_data = {};
                    ajax_data['_token'] = "{!! csrf_token() !!}";
                    ajax_data['inventory'] = parseInt(data.inventory) - parseInt(number);
                    ajax_data['out_inventory'] = parseInt(data.out_inventory) + parseInt(number);
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : "{{guard_url('onbuy/listing')}}"+'/'+data.product_id,
                        data : ajax_data,
                        type : 'PUT',
                        success : function (data) {
                            layer.close(load);
                            if(data.code == 0)
                            {
                                var nPage = $(".layui-laypage-curr em").eq(1).text();
                                //执行重载
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }else{
                                layer.msg(data.msg);
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            layer.msg('服务器出错');
                        }
                    });
                });
            }
        });
        table.on('edit(fb-table)', function(obj){
            var data = obj.data;
            var value = obj.value //得到修改后的值
                    ,data = obj.data //得到所在行所有键值
                    ,field = obj.field; //得到字段
            var ajax_data = {};
            ajax_data['_token'] = "{!! csrf_token() !!}";
            ajax_data[field] = value;
            // 加载样式
            var load = layer.load();
            $.ajax({
                url :   "{{guard_url('onbuy/listing')}}"+'/'+data.product_id,
                data : ajax_data,
                type : 'PUT',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0)
                    {

                    }else{
                        layer.msg(data.msg);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
        });

        var $ = layui.$;
        active = {
            reload: function(){
                var demoReload = $('#demoReload');
                var where = {};
                $(".search_key").each(function(){
                    var name = $(this).attr('name');
                    where["search["+name+"]"] = $(this).val();
                });
                //执行重载
                table.reload('fb-table', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: where
                    ,error:function(res, curr, count)
                    {
                        console.log(res)
                        $.ajax_error(jqXHR, textStatus, errorThrown);
                    }
                });
            },
        };
        $('.tabel-message .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        laydate.render({
            elem: '#date' //指定元素
            ,type: 'date'
            ,range: '~'
        });


    });
</script>

