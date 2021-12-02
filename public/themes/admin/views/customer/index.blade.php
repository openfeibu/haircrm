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
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-warm " type="button"><a href="{{ guard_url('customer/create') }}">{{ trans('app.add') }} {{ trans('customer.name') }}</a></button>
                            <button class="layui-btn layui-btn-warm " type="button"><a href="{{ guard_url('customer_import') }}">批量上传</a></button>
                            <button class="layui-btn layui-btn-primary " type="button" data-type="download" data-events="download">下载 Excel</button>
                            <button class="layui-btn layui-btn-danger " type="button" data-type="del" data-events="del">{{ trans('app.delete') }}</button>
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
                            <select name="stage" class="search_key layui-select">
                                <option value="">{{ trans('customer.label.stage') }}</option>
                                @foreach(config('model.customer.customer.stage') as $key => $stage)
                                    <option value="{{ $stage }}">{{ trans('customer.stage.'.$stage) }}</option>
                                @endforeach
                            </select>
                        </div>
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
                        <div class="layui-inline">
                            <button class="layui-btn" type="button" data-type="reload">{{ trans('app.search') }}</button>
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
    <a class="layui-btn layui-btn-normal layui-btn-sm" lay-event="follow_up" >跟进</a>
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('customer') }}/@{{ d.id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<div class="fb-main-table "id="follow_up" style="display: none">
    <form class="layui-form" action="" lay-filter="fb-form">
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">跟进日期 *</label>
            <div class="layui-input-block">
                <input type="text" name="date" id="follow_up_date" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">跟进内容 *</label>
            <div class="layui-input-block">
                <input type="text" name="content" id="follow_up_content" class="layui-input">
            </div>
        </div>
    </form>
</div>

<script>
    var main_url = "{{guard_url('customer')}}";
    var delete_all_url = "{{guard_url('customer/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        var laydate = layui.laydate;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('customer')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80,sort:true, fixed: 'left'}
                ,{field:'name',title:'{{ trans('customer.label.name') }}',edit:'text',fixed:'left'}
                ,{field:'salesman_name',title:'{{ trans('salesman.label.name') }}'}
                ,{field:'ig',title:'{{ trans('customer.label.ig') }}',templet:'<div>@{{# if(d.ig){ }}<a href="https://www.instagram.com/@{{ d.ig }}" target="_blank">@{{ d.ig }}</a>@{{# }  }}</div>'}
                ,{field:'from',title:'{{ trans('customer.label.from') }}',edit:'text'}
                ,{field:'email',title:'{{ trans('customer.label.email') }}',edit:'text'}
                ,{field:'mobile',title:'{{ trans('customer.label.mobile') }}',edit:'text'}
                ,{field:'imessage',title:'{{ trans('customer.label.imessage') }}',edit:'text'}
                ,{field:'whatsapp',title:'{{ trans('customer.label.whatsapp') }}',edit:'text'}
                ,{field:'address',title:'{{ trans('customer.label.address') }}',edit:'text'}
                ,{field:'order_count',title:'{{ trans('customer.label.order_count') }}',sort:true}
                ,{field:'last_paid',title:'{{ trans('customer.label.last_paid') }}',sort:true}
                ,{field:'total',title:'{{ trans('customer.label.total') }}',sort:true}
                ,{field:'stage_desc',title:'{{ trans('customer.label.stage') }}'}
                ,{field:'remark',title:'{{ trans('customer.label.remark') }}',edit:'text'}
                ,{field:'chat_app_account',title:'{{ trans('customer.label.chat_app_account') }}', width:240,edit:'text'}
                ,{field:'level',title:'{{ trans('customer.label.level') }}', width:240,edit:'text',sort:true}
                ,{field:'follow_up_date',title:'上次跟进',edit:'text'}
                ,{field:'follow_up_content',title:'跟进内容',edit:'text'}
                ,{field:'created_at',title:'{{ trans('app.created_at') }}', width:120}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:200, align: 'right',toolbar:'#barDemo', fixed: 'right'}
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
        laydate.render({
            elem: '#follow_up_date'
            ,type: 'datetime'
            ,value:"{{ date('Y-m-d H:i:s') }}"
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
            var url = '{{ guard_url('customer_download') }}';
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
        $.extend_tool = function (obj) {
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            data['nPage'] = $(".layui-laypage-curr em").eq(1).text();

            customer_handle[obj.event] ? customer_handle[obj.event].call(this,data) : '';
        };

        var customer_handle = {};
        customer_handle = {
            follow_up:function (obj) {
                var ajax_data = {};
                ajax_data['_token'] = "{!! csrf_token() !!}";

                layer.open({
                    type: 1,
                    shade: false,
                    title: '跟进', //不显示标题
                    area: ['620px', '440px'], //宽高
                    content: $('#follow_up'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {

                        var follow_up_date = $("#follow_up_date").val();
                        var follow_up_content = $("#follow_up_content").val();
                        if(!follow_up_date)
                        {
                            layer.msg("请填写跟进日期");
                            return false;
                        }
                        if(!follow_up_content)
                        {
                            layer.msg("请填写跟进内容");
                            return false;
                        }
                        ajax_data['customer_id'] = obj['id'];
                        ajax_data['date'] = follow_up_date;
                        ajax_data['content'] = follow_up_content;
                        var load =layer.load();
                        $.ajax({
                            url : "{{ guard_url('customer/follow_up') }}",
                            data : ajax_data,
                            type : 'POST',
                            success : function (data) {
                                layer.closeAll();
                                if(data.code == 0) {
                                    layer.msg(data.msg);
                                    console.log(obj);
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
                });
            },
        }
    })
</script>