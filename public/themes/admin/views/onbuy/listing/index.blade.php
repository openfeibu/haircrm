<style>
    .layui-table-cell{height:80px;}
    .layui-table-header .layui-table-cell, .layui-table-tool-panel li{white-space: pre-wrap !important;}
</style>
<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('onbuy/listing/index') }}"><cite>Onbuy{{ trans('goods.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm " type="button" data-type="sync" data-events="sync">从Onbuy同步新产品</button>
                    <button class="layui-btn layui-btn-warm " type="button" data-type="automatic" data-events="automatic">自动化定价</button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="sku" id="demoReload" placeholder="sku" autocomplete="off">
                </div>
                <div class="layui-inline">

                    <input class="layui-input search_key" name="name" id="demoReload" placeholder="名称" autocomplete="off">
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
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('goods') }}/@{{ d.goods_id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <a href="@{{ d.product_url }}" target="_blank"><img src="@{{d.image_url}}" alt="" height="58"></a>
</script>
<script type="text/html" id="productTEM">
    <div>
        <p> <a href="@{{ d.product_url }}" target="_blank">@{{ d.name }}</a></p>
        <p> sku: @{{ d.sku }}</p>
        <p> group_sku: @{{ d.group_sku }}</p>
    </div>
</script>
<div class="automatic_content" style="display: none">
    <form class="layui-form automatic_form" action="" style="margin: 10px 10px ">
        <!--
        <div><p>计划自动定价共：<span id="automatic_count">0</span>个产品</p></div>


        <div class="layui-form-item">
            <label class="layui-form-label">日期</label>
            <div class="layui-inline">
                开始:<input type="text" name="start_date" value=""  autocomplete="off" placeholder="" class="layui-input" id="start_date" >

            </div>
            <div class="layui-inline">
            结束:  <input type="text" name="end_date" value=""  autocomplete="off" placeholder="" class="layui-input" id="end_date" >
            </div>
        </div>
        -->
        <div class="layui-form-item">
            <label class="layui-form-label">时间</label>
            <div class="layui-inline">
                开始:<input type="text" name="start_time" value=""  autocomplete="off" placeholder="" class="layui-input" id="start_time" >

            </div>
            <div class="layui-inline">
                结束:  <input type="text" name="end_time" value=""  autocomplete="off" placeholder="" class="layui-input" id="end_time" >
            </div>
        </div>
        <!--
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">每天自动 *</label>

            <div class="layui-input-block">
                <input type="checkbox" name="everyday" value="0" lay-skin="switch" lay-text="是|否" lay-filter="active" class="everyday" >
            </div>

        </div>

        -->
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">{{ trans('mail_template.label.active') }} *</label>

            <div class="layui-input-block">
                <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" class="active" checked>
            </div>

        </div>
    </form>
</div>
<script>
    var main_url = "{{guard_url('onbuy/listing')}}";
    var delete_all_url = "{{guard_url('onbuy/listing/destroyAll')}}";

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
            ,url: main_url
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'image',title:'图片', width:120,templet:'#imageTEM',height:48, fixed: 'left'}
                ,{field:'name',title:'{{ trans('goods.name') }}',width:250,templet:'#productTEM'}
                ,{field:'price',title:'销售价GBP<i class="layui-icon alone-tips analyseTips" lay-tips="同步时的销售价,非及时更新,以onbuy为准">&#xe60b;</i>', width:100, edit:'text'}
                ,{field:'min_price',title:'最低价GBP<i class="layui-icon alone-tips analyseTips" lay-tips="用于追踪更新最低价竞争使用">&#xe60b;</i>', width:100,edit:'text'}
                ,{field:'min_price_expect',title:'最低价预计到账RMB<i class="layui-icon alone-tips analyseTips" lay-tips="扣除Onbuy所有费用(16.7% + 9%)和paypal费用(5%)后的资金RMB">&#xe60b;</i>', width:120}
                ,{field:'min_price_profit_expect',title:'最低价预计利润RMB', width:100}
                ,{field:'original_price',title:'原价GBP<i class="layui-icon alone-tips analyseTips" lay-tips="用于更新最低价后恢复原价使用">&#xe60b;</i>', width:100,edit:'text'}
                ,{field:'original_price_expect',title:'原价预计到账RMB<i class="layui-icon alone-tips analyseTips" lay-tips="扣除Onbuy所有费用(16.7% + 9%)和paypal费用(5%)后的资金RMB">&#xe60b;</i>', width:120}
                ,{field:'original_price_profit_expect',title:'原价预计利润RMB', width:100}
                ,{field:'purchase_price',title:'采购RMB', width:100,edit:'text'}
                ,{field:'weight',title:'重量/g', width:100, edit:'text'}
                ,{field:'freight_expect',title:'预计运费RMB', width:100}
                ,{field:'min_price_advice',title:'建议最低价GBP', width:100}
                ,{field:'stock',title:'库存', width:100}
                ,{field:'product_listing_id',title:'product_listing_id', width:100}
                ,{field:'condition',title:'condition', width:100}
                ,{field:'handling_time',title:'处理时间', width:100}
                ,{field:'boost_marketing_commission',title:'推广', width:80}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ $limit }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,done:function () {
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
            ajax_data['list_id'] = data.list_id;
            // 加载样式
            var load = layer.load();
            $.ajax({
                url :  main_url+'/'+data.id,
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
            del:function(){
                var checkStatus = table.checkStatus('fb-table')
                        ,data = checkStatus.data;
                var data_id_obj = {};
                var i = 0;
                data.forEach(function(v){ data_id_obj[i] = v.list_id; i++});
                data.length == 0 ?
                        layer.msg('请选择要删除的数据', {
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        })
                        :
                        layer.confirm('是否删除已选择的数据',{title:'提示'},function(index){
                            layer.close(index);
                            var load = layer.load();
                            $.ajax({
                                url : delete_all_url,
                                data :  {'list_ids':data_id_obj,'_token' : "{!! csrf_token() !!}"},
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
            sync: function () {
                layer.confirm('是否同步(该同步只会导出新产品,不会更新旧产品信息)',{title:'提示'},function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : main_url+'/sync',
                        data :  {'_token' : "{!! csrf_token() !!}"},
                        type : 'POST',
                        success : function (data) {
                            layer.close(load);
                            layer.msg(data.msg);
                            if(data.code == 0)
                            {
                                var nPage = $(".layui-laypage-curr em").eq(1).text();
                                //执行重载
                                table.reload('fb-table', {
                                    page: {
                                        curr: nPage //重新从第 1 页开始
                                    }
                                });
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                })  ;
            },
            automatic:function () {
                var checkStatus = table.checkStatus('fb-table')
                        ,data = checkStatus.data;
                var data_id_obj = {};
                if(data.length == 0)
                {
                    layer.msg('请选择数据', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    });
                    return;
                }
                var ajax_data = {'_token':"{!! csrf_token() !!}"};
                var i = 0;
                var count = 0;
                var skus = [];
                data.forEach(function(v){
                    skus.push(v.sku);
                    count++;
                });

                ajax_data['skus'] = skus;
                layer.open({
                    type: 1,
                    shade: false,
                    title: '{{ trans('app.add') }}', //不显示标题
                    area: ['620px', '440px'], //宽高
                    content: $('.automatic_content'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {
                        var active = 0;
                        if($(".active").prop("checked")){
                            active = 1;
                        }
                       /* var everyday = 0;
                        if($(".everyday").prop("checked")){
                            everyday = 1;
                        }*/
                        ajax_data['active'] = active;
                        ajax_data['everyday'] = 1;
                        //ajax_data['start_date'] = $('input[name=start_date]').val();
                        //ajax_data['end_date'] = $('input[name=end_date]').val();
                        ajax_data['start_time'] = $('input[name=start_time]').val();
                        ajax_data['end_time'] = $('input[name=end_time]').val();

                        var load =layer.load();
                        $.ajax({
                            url : main_url + '/automatic',
                            data : ajax_data,
                            type : 'POST',
                            success : function (data) {
                                layer.closeAll();
                                layer.msg(data.msg);
                               /* if(data.code == 0) {
                                    window.location.href=data.url;
                                }else{
                                    layer.msg(data.msg);
                                }*/
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                });
            }
        };
        $('.tabel-message .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        laydate.render({
            elem: '#start_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d') !!}"
        });
        laydate.render({
            elem: '#end_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d',strtotime('+1 day +1 hour +1 minute')) !!}"
        });
        laydate.render({
            elem: '#start_time'
            ,type: 'time'
            ,value: "23:30:00"
            //,format:"HH:mm"
        });
        laydate.render({
            elem: '#end_time'
            ,type: 'time'
            ,value: "06:30:00"
        });
    });
</script>

