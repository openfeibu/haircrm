<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('new_customer') }}"><cite>{{ trans('new_customer.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-warm "  type="button"><a href="{{ guard_url('new_customer/create') }}">{{ trans('app.add') }} {{ trans('new_customer.name') }}</a></button>
                            <button class="layui-btn layui-btn-warm "  type="button"><a href="{{ guard_url('new_customer_import') }}">批量上传</a></button>
                            <button class="layui-btn layui-btn-primary " type="button" data-type="download" data-events="download">下载 Excel</button>
                            <button class="layui-btn layui-btn-danger "  type="button" data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                        </div>
                    </div>
                    <div class="layui-block table-search mb10">
                        <div class="layui-inline">
                            <select name="salesman_id" class="search_key layui-select">
                                @inject('salesmanRepository','App\Repositories\Eloquent\SalesmanRepository')
                                <option value="">{{ trans('salesman.name') }}</option>
                                @foreach($salesmanRepository->orderBy('name','asc')->orderBy('id','desc')->get() as $key => $salesman)
                                    <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="nickname" id="demoReload" placeholder="{{ trans('new_customer.label.nickname') }}" autocomplete="off">
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
                        <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>
                    </div>
                </form>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    @{{# if(d.mark == 'new'){ }}
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('customer/create') }}?new_customer_id=@{{ d.id }}">下单客户</a>
    @{{# } }}
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('new_customer') }}/@{{ d.id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<script>
    var main_url = "{{guard_url('new_customer')}}";
    var delete_all_url = "{{guard_url('new_customer/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('new_customer')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80}
                ,{field:'salesman_name',title:'{{ trans('salesman.label.name') }}',sort:true}
                ,{field:'company_name',title:'{{ trans('new_customer.label.company_name') }}',edit:'text'}
                ,{field:'company_website',title:'{{ trans('new_customer.label.company_website') }}',edit:'text'}
                ,{field:'nickname',title:'{{ trans('new_customer.label.nickname') }}',edit:'text'}
                ,{field:'email',title:'{{ trans('new_customer.label.email') }}',edit:'text'}
                ,{field:'mobile',title:'{{ trans('new_customer.label.mobile') }}',edit:'text'}
                ,{field:'imessage',title:'{{ trans('new_customer.label.imessage') }}',edit:'text'}
                ,{field:'whatsapp',title:'{{ trans('new_customer.label.whatsapp') }}',edit:'text'}
                ,{field:'main_product',title:'{{ trans('new_customer.label.main_product') }}',edit:'text'}
                ,{field:'ig',title:'{{ trans('new_customer.label.ig') }}',edit:'text'}
                ,{field:'ig_follower_count',title:'{{ trans('new_customer.label.ig_follower_count') }}',edit:'text'}
                ,{field:'ig_sec',title:'{{ trans('new_customer.label.ig_sec') }}',edit:'text'}
                ,{field:'facebook',title:'{{ trans('new_customer.label.facebook') }}',edit:'text'}
                ,{field:'remark',title:'{{ trans('new_customer.label.remark') }}'}
                ,{field:'mark_desc',title:'{{ trans('new_customer.label.mark') }}', width:100, fixed: 'right'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:240, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
<script>
    layui.use(['jquery','element','table'], function() {
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        active.download = function () {
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = '{{ guard_url('new_customer_download') }}';
            var paramStr = "";
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?ids[]="+v.id;
                }else{
                    paramStr += "&ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            $(".search_key").each(function(){
                var name = $(this).attr('name');
                if(i == 0)
                {
                    paramStr += "?search["+name+"]="+$(this).val();
                }else{
                    paramStr += "&search["+name+"]="+$(this).val();
                }
                i++
            });
            var load =layer.load();
            window.location.href = url+paramStr;
            layer.close(load);

        }

    })
</script>