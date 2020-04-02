<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('order') }}"><cite>{{ trans('order.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('order/create') }}">{{ trans('app.add') }} {{ trans('order.name') }}</a></button>
                    <button class="layui-btn layui-btn-danger " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                    <button class="layui-btn layui-btn-primary " data-type="download_purchase_order" data-events="download_purchase_order">下载采购表</button>
                    <button class="layui-btn layui-btn-primary " data-type="download_quotation_list" data-events="download_quotation_list">下载报价表</button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="order_sn" id="demoReload" placeholder="{{ trans('order.label.order_sn') }}" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<div class="fb-main-table "id="payment_content" style="display: none">
    <form class="layui-form" action="" lay-filter="fb-form">
        <div class="layui-form-item fb-form-item">
            <label class="layui-form-label">{{ trans('payment.name') }} *</label>

            <div class="layui-input-block">
                @inject('paymentRepository','App\Repositories\Eloquent\PaymentRepository')
                <select name="payment_id" id="payment_id">
                    @foreach($paymentRepository->orderBy('id','asc')->get() as $key => $payment)
                        <option value="{{ $payment->id }}">{{ $payment->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">{{ trans('order.label.payment_sn') }} *</label>
            <div class="layui-input-block">
                <input type="text" name="payment_sn" id="payment_sn" class="layui-input">
            </div>
        </div>
    </form>
</div>
<div class="fb-main-table "id="to_delivery_content" style="display: none">
    <form class="layui-form" action="" lay-filter="fb-form">
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">{{ trans('order.label.tracking_number') }} *</label>
            <div class="layui-input-block">
                <input type="text" name="tracking_number" id="tracking_number" class="layui-input">
            </div>
        </div>
    </form>
</div>
<script type="text/html" id="barDemo">
    @include('order/handle')
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('order') }}/@{{ d.id }}" >{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
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
    var main_url = "{{guard_url('order')}}";
    var delete_all_url = "{{guard_url('order/destroyAll')}}";
    var order_lang = eval({!! json_encode(trans('order')) !!});
    var order_btn_class = eval({!! json_encode(config('model.order.order.btn_class')) !!});
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;

        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('order')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80}
                ,{field:'order_sn',title:'{{ trans('order.label.order_sn') }}'}
                ,{field:'salesman_name',title:'{{ trans('salesman.label.name') }}', width:120}
                ,{field:'customer_name',title:'{{ trans('customer.label.name') }}'}
                ,{field:'address',title:'{{ trans('app.address') }}', width:120}
                ,{field:'purchase_price',title:'{{ trans('order.label.purchase_price') }}', width:120}
                ,{field:'selling_price',title:'{{ trans('order.label.selling_price') }}', width:120}
                ,{field:'number',title:'{{ trans('order.label.number') }}', width:120}
                ,{field:'number',title:'{{ trans('order.label.number') }}', width:120}
                ,{field:'order_status_desc',title:'{{ trans('order.label.order_status') }}', width:120,templet:"#order_status_tpl"}
                ,{field:'shipping_status_desc',title:'{{ trans('order.label.shipping_status') }}', width:120,templet:"#shipping_status_tpl"}
                ,{field:'pay_status_desc',title:'{{ trans('order.label.pay_status') }}', width:120,templet:"#pay_status_tpl"}
                ,{field:'tracking_number',title:'{{ trans('order.label.tracking_number') }}', width:120}
                ,{field:'payment_sn',title:'{{ trans('order.label.payment_sn') }}', width:120}
                ,{field:'created_at',title:'{{ trans('app.created_at') }}', width:120}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:280, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,done:function () {
                element.init();
            }
        });
        //监听在职操作
        form.on('switch(active)', function(obj){
            var data = $(obj.elem);
            var id = data.parents('tr').first().find('td').eq(1).text();
            var ajax_data = {};
            ajax_data['_token'] = "{!! csrf_token() !!}";
            ajax_data['active'] = obj.elem.checked == true ? 1 : 0;
            var load = layer.load();
            $.ajax({
                url : main_url+'/'+id,
                data : ajax_data,
                type : 'PUT',
                success : function (data) {
                    layer.close(load);
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
        });

    });
</script>

{!! Theme::partial('common_handle_js') !!}
<script>
    layui.use(['jquery','element','table'], function() {
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        active.download_purchase_order = function () {
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = '{{ guard_url('order_download/purchase_order') }}';
            var paramStr = "";
            if(data.length == 0)
            {
                layer.msg('请选择数据', {
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                })
                return false;
            }
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?ids[]="+v.id;
                }else{
                    paramStr += "&ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            window.location.href=url+paramStr;
        }
        active.download_quotation_list = function () {
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = '{{ guard_url('order_download/quotation_list') }}';
            var paramStr = "";
            if(data.length == 0)
            {
                layer.msg('请选择数据', {
                    time: 2000 //2秒关闭（如果不配置，默认是3秒）
                })
                return false;
            }
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?ids[]="+v.id;
                }else{
                    paramStr += "&ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            window.location.href=url+paramStr;
        }
//        $('.order-btn').on('click', function(){
//            var type = $(this).attr('type');
//            order_handle[type] ? order_handle[type].call(this) : '';
//        });
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            data['nPage'] = $(".layui-laypage-curr em").eq(1).text();

            order_handle[obj.event] ? order_handle[obj.event].call(this,data) : '';
        }
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
                                    layer.msg(data.message);
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
                                    layer.msg(data.message);
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
                                    layer.msg(data.message);
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
                                    layer.msg(data.message);
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
                                layer.msg(data.message);
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
                                layer.msg(data.message);
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
                                layer.msg(data.message);
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

    })
</script>