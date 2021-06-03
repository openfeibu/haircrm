<script type="text/html" id="barDemo">
    @{{# if(typeof(d.operation.confirm) != "undefined" && d.operation.confirm) { }}
    <a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="confirm">@{{ order_lang['operation']['confirm'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.pay) != "undefined" && d.operation.pay) { }}
    <a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="pay">@{{ order_lang['operation']['pay'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.unpay) != "undefined" && d.operation.unpay) { }}
    <a class="layui-btn layui-btn-warm layui-btn-sm order-btn" lay-event="unpay">@{{ order_lang['operation']['unpay'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.unship) != "undefined" && d.operation.unship) { }}
    <a class="layui-btn layui-btn-warm layui-btn-sm order-btn" lay-event="unship">@{{ order_lang['operation']['unship'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.to_delivery) != "undefined" && d.operation.to_delivery) { }}
    <a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="to_delivery">@{{ order_lang['operation']['to_delivery'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.receive) != "undefined" && d.operation.receive) { }}
    <a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="receive">@{{ order_lang['operation']['receive'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.cancel) != "undefined" && d.operation.cancel) { }}
    <a class="layui-btn layui-btn-danger layui-btn-sm order-btn" lay-event="cancel">@{{ order_lang['operation']['cancel'] }}</a>
    @{{#  } }}
    @{{# if(typeof(d.operation.return) != "undefined" && d.operation.return) { }}
    <a class="layui-btn layui-btn-danger layui-btn-sm order-btn" lay-event="return_order">@{{ order_lang['operation']['return'] }}</a>
    @{{#  } }}
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('order') }}/@{{ d.id }}" >{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    <a class="layui-btn layui-btn-primary layui-btn-sm" lay-event="new_window">{{ trans('goods.name') }}</a>
</script>
<script type="text/html" id="order_status_tpl">
    <div>
        <a class='layui-btn layui-btn-sm @{{order_btn_class['order_status'][d.order_status]}}'>@{{order_lang['order_status'][d.order_status]}}</a>
    </div>
</script>
<script type="text/html" id="shipping_status_tpl">
    <div>
        <a class='layui-btn layui-btn-sm @{{order_btn_class['shipping_status'][d.shipping_status]}}'>@{{order_lang['shipping_status'][d.shipping_status]}}</a>
    </div>
</script>
<script type="text/html" id="pay_status_tpl">
    <div>
        <a class='layui-btn layui-btn-sm @{{order_btn_class['pay_status'][d.pay_status]}}'>@{{order_lang['pay_status'][d.pay_status]}}</a>
    </div>
</script>

<script>
    var order_handle = {};
    var order_lang = eval({!! json_encode(trans('order')) !!});
    var order_btn_class = eval({!! json_encode(config('model.order.order.btn_class')) !!});
    layui.use(['jquery','element','table'], function() {
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        order_handle = {
            confirm:function (obj) {

            },
            pay:function (obj) {
                layer.open({
                    type: 1
                    ,skin: 'layui-layer-rim' //加上边框
                    ,area: ['520px', '240px'] //宽高,
                    ,title:'{{ trans('order.operation.pay') }}'
                    ,content: $("#payment_content")
                    ,shadeClose:true
                    ,btn: ['确认', '取消']
                    ,yes: function(index, layero){
                        var payment_id = $("#payment_id").val();
                        var payment_sn = $("#payment_sn").val();
                        if(!payment_id)
                        {
                            layer.msg('请选择支付方式');
                            return false;
                        }
                        if(!payment_sn)
                        {
                            layer.msg('请填写支付单号');
                            return false;
                        }
                        var load = layer.load();
                        $.ajax({
                            url : "{{ guard_url('order/pay') }}",
                            data :  {'id':obj['id'],'_token' : "{!! csrf_token() !!}",'payment_id':payment_id,'payment_sn':payment_sn},
                            type : 'POST',
                            success : function (data) {
                                layer.closeAll();
                                if(data.code == 0)
                                {
                                    layer.msg(data.msg);
                                    if(typeof(obj['nPage']) != "undefined") {
                                        var nPage = $(".layui-laypage-curr em").eq(1).text();
                                        //执行重载
                                        table.reload('fb-table', {
                                            page: {
                                                curr: nPage //重新从第 1 页开始
                                            }
                                        });
                                    }
                                }else{
                                    layer.msg(data.msg);
                                }
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                    ,btn2: function(index, layero){

                    }
                });
            },
            to_delivery:function (obj) {
                layer.open({
                    type: 1
                    ,skin: 'layui-layer-rim' //加上边框
                    ,area: ['520px', '240px'] //宽高,
                    ,title:'{{ trans('order.operation.to_delivery') }}'
                    ,content: $("#to_delivery_content")
                    ,shadeClose:true
                    ,btn: ['确认', '取消']
                    ,yes: function(index, layero){
                        var tracking_number = $("#tracking_number").val();
                        if(!tracking_number)
                        {
                            layer.msg('请填写运单号');
                            return false;
                        }
                        var load = layer.load();
                        $.ajax({
                            url : "{{ guard_url('order/to_delivery') }}",
                            data :  {'id':obj['id'],'_token' : "{!! csrf_token() !!}",'tracking_number':tracking_number},
                            type : 'POST',
                            success : function (data) {
                                layer.closeAll();
                                if(data.code == 0)
                                {
                                    layer.msg(data.msg);
                                    if(typeof(obj['nPage']) != "undefined") {
                                        var nPage = $(".layui-laypage-curr em").eq(1).text();
                                        //执行重载
                                        table.reload('fb-table', {
                                            page: {
                                                curr: nPage //重新从第 1 页开始
                                            }
                                        });
                                    }
                                }else{
                                    layer.msg(data.msg);
                                }
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                    ,btn2: function(index, layero){

                    }
                });
            },
            new_window: function (obj) {
                var goods_list = '<table class="layui-table">\
                        <thead>\
                        <tr>\
                        <th width="380">{{ trans('goods.name') }}</th>\
                        <th width="120">{{ trans('goods.label.size') }}</th>\
                        <th width="80">{{ trans('app.number') }}</th>\
                        <th width="120">{{ trans('app.remark') }}</th>\
                        </tr>\
                        </thead>\
                        <tbody>';
                $.each(obj['goods_list'], function (index, value) {
                    goods_list += '<tr><td>'+value.goods_name+'</td><td>'+value.attribute_value+'</td><td>'+value.number+'</td><td>'+value.remark+'</td></tr>';
                });

                goods_list +='</tbody></table>';
                layer.open({
                    type: 1,
                    title: false,
                    area:['600px', '60%'],
                    closeBtn: 1,
                    shadeClose: true,
                    content: goods_list
                });

                /*
                 layer.open({
                 type: 2,
                 title: false,
                 area: ['920px', '660px'],
                 shade: 0.8,
                 closeBtn: 1,
                 shadeClose: true,
                 content: "{{ guard_url('order') }}/"+obj['id']
                 });
                 */
            },
            cancel:function (obj) {
                layer.confirm('该操作无法撤回，确定取消吗？',{title:'提示'},function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('order/cancel') }}",
                        data :  {'id':obj['id'],'_token' : "{!! csrf_token() !!}"},
                        type : 'POST',
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
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                })  ;
            },
            receive:function (obj) {
                layer.confirm('该操作无法撤回，确定已收获吗？',{title:'提示'},function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('order/receive') }}",
                        data :  {'id':obj['id'],'_token' : "{!! csrf_token() !!}"},
                        type : 'POST',
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
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                })  ;
            },
            return_order:function (obj) {
                layer.confirm('该操作无法撤回，确定退货吗？',{title:'提示'},function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('order/return') }}",
                        data :  {'id':obj['id'],'_token' : "{!! csrf_token() !!}"},
                        type : 'POST',
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
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                })  ;
            }
        }
    });
</script>