<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('supplier') }}"><cite>{{ trans('supplier.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-warm "  type="button"><a href="{{ guard_url('supplier/create') }}">{{ trans('app.add') }} {{ trans('supplier.name') }}</a></button>
                            <button class="layui-btn layui-btn-danger "  type="button" data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                        </div>
                    </div>
                    <div class="layui-block table-search mb10">
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="name" id="demoReload" placeholder="{{ trans('supplier.label.name') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>
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

    <a class="layui-btn layui-btn-sm" href="{{ guard_url('supplier') }}/@{{ d.id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<script>
    var main_url = "{{guard_url('supplier')}}";
    var delete_all_url = "{{guard_url('supplier/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        form.render();
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('supplier')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80,fixed:'left'}
                ,{field:'name',title:'{{ trans('supplier.label.name') }}', width:120,sort:true}
                ,{field:'url',title:'{{ trans('supplier.label.url') }}',templet:'<div>@{{# if(d.url){ }}<a href="@{{ d.url }}" target="_blank">@{{ d.url }}</a>@{{# }  }}</div>'}
                ,{field:'code',title:'{{ trans('supplier.label.code') }}',edit:'text', width:100}
                ,{field:'remark',title:'{{ trans('supplier.label.remark') }}',edit:'text', fixed: 'right'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:150, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
    });
</script>

{!! Theme::partial('common_handle_js') !!}
<script>
    layui.use(['jquery','element','table'], function() {
        var $ = layui.$;
        var table = layui.table;
        var element = layui.element;
        var form = layui.form;



    })
</script>