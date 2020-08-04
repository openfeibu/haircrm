<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('mail_schedule') }}"><cite>{{ trans('mail_schedule.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-danger " type="button" data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('mail_schedule') }}/@{{ d.id }}">{{ trans('mail_schedule_report.name') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<script type="text/html" id="activeTpl">
    <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" @{{ d.active == 1 ? 'checked' : '' }}>
</script>

<script>
    var main_url = "{{guard_url('mail_schedule')}}";
    var delete_all_url = "{{guard_url('mail_schedule/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('mail_schedule')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80,sort:true}
                ,{field:'title',title:'{{ trans('mail_schedule.label.title') }}',edit:'text'}
                ,{field:'interval',title:'{{ trans('mail_schedule.label.interval') }}',edit:'text'}
                ,{field:'per_hour_mail',title:'{{ trans('mail_schedule.label.per_hour_mail') }}',edit:'text'}
                ,{field:'mail_count',title:'{{ trans('mail_schedule.label.mail_count') }}'}
                ,{field:'send_count',title:'{{ trans('mail_schedule.label.send_count') }}'}
                ,{field:'success_count',title:'{{ trans('mail_schedule.label.success_count') }}'}
                ,{field:'failed_count',title:'{{ trans('mail_schedule.label.failed_count') }}'}
                ,{field:'last_at',title:'{{ trans('mail_schedule.label.last_at') }}', width:180}
                ,{field:'status_desc',title:'{{ trans('mail_schedule.label.status') }}'}
                ,{field:'active',title:'{{ trans('mail_template.label.active') }}',templet:'#activeTpl'}
                ,{field:'account_usernames',title:'{{ trans('mail_account.name') }}'}
                ,{field:'template_names',title:'{{ trans('mail_template.name') }}'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'160'
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
