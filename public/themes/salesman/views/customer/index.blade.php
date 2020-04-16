<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('customer') }}"><cite>{{ trans('customer.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-block mb10">
                    <div class="layui-inline tabel-btn">
                        <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('customer/create') }}">{{ trans('app.add') }} {{ trans('customer.name') }}</a></button>
                        <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('customer_import') }}">批量上传</a></button>
                        <button class="layui-btn layui-btn-danger " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                    </div>
                </div>
                <div class="layui-block mb10">
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="name" id="demoReload" placeholder="{{ trans('customer.label.name') }}" autocomplete="off">
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="email" id="demoReload" placeholder="{{ trans('new_customer.label.email') }}" autocomplete="off">
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="mobile" id="demoReload" placeholder="{{ trans('new_customer.label.mobile') }}" autocomplete="off">
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="ig" id="demoReload" placeholder="{{ trans('new_customer.label.ig') }}" autocomplete="off">
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="imessage" id="demoReload" placeholder="{{ trans('new_customer.label.imessage') }}" autocomplete="off">
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="whatsapp" id="demoReload" placeholder="{{ trans('new_customer.label.whatsapp') }}" autocomplete="off">
                    </div>
                    <button class="layui-btn" data-type="reload">{{ trans('app.search') }}</button>
                </div>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('customer') }}/@{{ d.id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<script>
    var main_url = "{{guard_url('customer')}}";
    var delete_all_url = "{{guard_url('customer/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('customer')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80}
                ,{field:'name',title:'{{ trans('customer.label.name') }}',edit:'text'}
                ,{field:'salesman_name',title:'{{ trans('salesman.label.name') }}'}
                ,{field:'ig',title:'{{ trans('customer.label.ig') }}',edit:'text'}
                ,{field:'from',title:'{{ trans('customer.label.from') }}',edit:'text'}
                ,{field:'email',title:'{{ trans('customer.label.email') }}',edit:'text'}
                ,{field:'mobile',title:'{{ trans('customer.label.mobile') }}',edit:'text'}
                ,{field:'imessage',title:'{{ trans('customer.label.imessage') }}',edit:'text'}
                ,{field:'whatsapp',title:'{{ trans('customer.label.whatsapp') }}',edit:'text'}
                ,{field:'address',title:'{{ trans('customer.label.address') }}',edit:'text'}
                ,{field:'order_count',title:'{{ trans('customer.label.order_count') }}',edit:'text'}
                ,{field:'remark',title:'{{ trans('customer.label.remark') }}',edit:'text'}
                ,{field:'chat_app_account',title:'{{ trans('customer.label.chat_app_account') }}',edit:'text'}
                ,{field:'level',title:'{{ trans('customer.label.level') }}', width:240,edit:'text',sort:true}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
