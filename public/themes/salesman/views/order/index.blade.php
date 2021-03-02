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
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-row mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-warm "  type="button"><a href="{{ guard_url('order/create') }}">{{ trans('app.add') }} {{ trans('order.name') }}</a></button>
                            <button class="layui-btn layui-btn-danger " data-type="del" data-events="del"  type="button">{{ trans('app.delete') }}</button>
                            <button class="layui-btn layui-btn-primary " data-type="download_quotation_list" data-events="download_quotation_list"  type="button">下载报价表</button>
                        </div>
                    </div>
                    <div class="layui-block mb10">
                        <div class="layui-inline">
                            @inject('customerRepository','App\Repositories\Eloquent\CustomerRepository')
                            <select name="customer_id" id="customer_id" class="search_key layui-select" lay-filter="customer" lay-search>
                                <option value="">{{ trans('customer.name') }}</option>
                                @foreach($customerRepository->where('salesman_id',Auth::user()->id)->orderBy('name','asc')->orderBy('id','desc')->get() as $key => $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}@if($customer->ig) ({{ $customer->ig }})@endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="order_status" class="layui-select search_key">
                                <option value="">{{ trans('order.label.order_status') }}</option>
                                @foreach(trans('order.order_status') as $key => $order_status)
                                    <option value="{{ $key }}">{{ $order_status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="shipping_status" class="layui-select search_key">
                                <option value="">{{ trans('order.label.shipping_status') }}</option>
                                @foreach(trans('order.shipping_status') as $key => $shipping_status)
                                    <option value="{{ $key }}">{{ $shipping_status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <select name="pay_status" class="layui-select search_key">
                                <option value="">{{ trans('order.label.pay_status') }}</option>
                                @foreach(trans('order.pay_status') as $key => $pay_status)
                                    <option value="{{ $key }}">{{ $pay_status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="order_sn" id="demoReload" placeholder="{{ trans('order.label.order_sn') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" data-type="reload"  type="button">{{ trans('app.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
@include('order/handle_content')

<script>
    var main_url = "{{guard_url('order')}}";
    var delete_all_url = "{{guard_url('order/destroyAll')}}";
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
                ,{field:'customer_name',title:'{{ trans('customer.label.name') }}', templet: '<div>@{{#  if(d.customer_id){ }}<a href="{{ guard_url('customer')}}/@{{ d.customer_id }}" target="_blank" class="layui-table-link">@{{d.customer_name}}</a>@{{#  } else { }}  @{{#  } }} </div>'}
                ,{field:'address',title:'{{ trans('order.label.address') }}', width:200}
                ,{field:'selling_price',title:'{{ trans('order.label.selling_price') }}', width:120}
                ,{field:'number',title:'{{ trans('order.label.number') }}', width:120}
                ,{field:'weight',title:'{{ trans('order.label.weight') }}', width:120}
                ,{field:'freight',title:'{{ trans('order.label.freight') }}', width:120}
                ,{field:'paypal_fee',title:'{{ trans('order.label.paypal_fee') }}', width:120}
                ,{field:'total',title:'{{ trans('order.label.total') }}', width:120}
                ,{field:'order_status_desc',title:'{{ trans('order.label.order_status') }}', width:120,templet:"#order_status_tpl"}
                ,{field:'shipping_status_desc',title:'{{ trans('order.label.shipping_status') }}', width:120,templet:"#shipping_status_tpl"}
                ,{field:'pay_status_desc',title:'{{ trans('order.label.pay_status') }}', width:120,templet:"#pay_status_tpl"}
                ,{field:'tracking_number',title:'{{ trans('order.label.tracking_number') }}', templet: '<div>@{{#  if(d.tracking_number){ }}<a href="/tracking_number/@{{d.tracking_number}}" target="_blank" class="layui-table-link">@{{d.tracking_number}}</a>@{{#  } else { }}  @{{#  } }} </div>',width:150}
                ,{field:'payment_sn',title:'{{ trans('order.label.payment_sn') }}', templet: '<div>@{{#  if(d.payment_sn){ }}<a href="/payment_sn/@{{d.payment_sn}}" target="_blank" class="layui-table-link">@{{d.payment_sn}}</a>@{{#  } else { }}  @{{#  } }} </div>', width:180}
                ,{field:'remark',title:'{{ trans('app.remark') }}',edit:'text', width:120}
                ,{field:'created_at',title:'{{ trans('app.created_at') }}', width:120}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:310, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
@include('order/handle_js')

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
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            data['nPage'] = $(".layui-laypage-curr em").eq(1).text();

            order_handle[obj.event] ? order_handle[obj.event].call(this,data) : '';
        }
    })
</script>