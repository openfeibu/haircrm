<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('salesman') }}"><cite>{{ trans('salesman.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-inline tabel-btn">
                        <button class="layui-btn layui-btn-warm "><a href="{{ guard_url('salesman/create') }}">{{ trans('app.add') }} {{ trans('salesman.name') }}</a></button>
                        <button class="layui-btn layui-btn-danger " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                    </div>
                    <div class="layui-inline">
                        <input class="layui-input search_key" name="name" id="demoReload" placeholder="{{ trans('salesman.label.name') }}" autocomplete="off">
                    </div>
                    <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>
                </form>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('salesman') }}/@{{ d.id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<script type="text/html" id="activeTpl">
    <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" @{{ d.active == 1 ? 'checked' : '' }}>
</script>
<script>
    var main_url = "{{guard_url('salesman')}}";
    var delete_all_url = "{{guard_url('salesman/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('salesman')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80}
                ,{field:'email',title:'{{ trans('salesman.label.email') }}',edit:'text'}
                ,{field:'name',title:'{{ trans('salesman.label.name') }}',edit:'text'}
                ,{field:'en_name',title:'{{ trans('salesman.label.en_name') }}',edit:'text'}
                ,{field:'entry_date',title:'{{ trans('salesman.label.entry_date') }}'}
                ,{field:'ig',title:'{{ trans('salesman.label.ig') }}',edit:'text'}
                ,{field:'imessage',title:'{{ trans('salesman.label.imessage') }}',edit:'text'}
                ,{field:'mobile',title:'{{ trans('salesman.label.mobile') }}',edit:'text'}
                ,{field:'monthly_performance_target',title:'{{ trans('salesman.label.monthly_performance_target') }}',edit:'text'}
                ,{field:'yearly_performance_target',title:'{{ trans('salesman.label.yearly_performance_target') }}',edit:'text'}
                ,{field:'customer_count',title:'{{ trans('salesman.label.customer_count') }}',width:'120'}
                ,{field:'new_customer_count',title:'{{ trans('salesman.label.new_customer_count') }}',width:'120'}
                ,{field:'today_new_customer_count',title:'{{ trans('salesman.label.today_new_customer_count') }}',width:'150'}
                ,{field:'yesterday_new_customer_count',title:'{{ trans('salesman.label.yesterday_new_customer_count') }}',width:'150'}
                ,{field:'order',title:'{{ trans('app.order') }}'}
                ,{field:'active',title:'{{ trans('salesman.label.active') }}',width:120,templet:'#activeTpl'}
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
