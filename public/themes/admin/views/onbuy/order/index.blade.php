<style>
    .layui-table-cell{height:80px;}
    .layui-table-header .layui-table-cell, .layui-table-tool-panel li{white-space: pre-wrap !important;}
</style>
<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('onbuy/order/index') }}"><cite>Onbuy 订单</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm " type="button" data-type="sync" data-events="sync">从Onbuy同步新订单</button>
                    <button class="layui-btn layui-btn-warm " type="button" data-type="sync_update" data-events="sync_update">从Onbuy同步更新订单</button>
                    <!--<button class="layui-btn layui-btn-warm " data-type="mark_purchase" data-events="mark_purchase">标记为已拿货</button>-->
                    <button class="layui-btn layui-btn-danger " data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                </div>
            </div>
            <div class="tabel-message">
                <div class="layui-inline">
                    <label class="layui-form-label">下单日期 *</label>
                    <div class="layui-input-block">
                        <input type="text" name="date" id="date" class="layui-input search_key">
                    </div>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="onbuy_order_products.sku" id="demoReload" placeholder="sku" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="onbuy_order_products.name" id="demoReload" placeholder="名称" autocomplete="off">
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
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('onbuy/order') }}/@{{ d.order_id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script type="text/html" id="imageTEM">
    <a href="@{{ d.product_url }}" target="_blank"><img src="@{{d.image_urls.thumb}}" alt="" height="58"></a>
</script>
<script type="text/html" id="productTEM">
    <div>
        <p> <a href="@{{ d.product_url }}" target="_blank">@{{ d.name }}</a></p>
        <p> sku: @{{ d.sku }} ; 数量: @{{ d.quantity }}</p>
        <p> 发货: @{{ d.expected_dispatch_date }}</p>
    </div>
</script>

<script>
    var main_url = "{{guard_url('onbuy/order')}}";
    var delete_all_url = "{{guard_url('onbuy/order/destroyAll')}}";

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
            ,id:'fb-table'
            ,url: main_url
            ,cols: [[
                {checkbox: true,field:'id', fixed: true}
                ,{field:'order_id',title:'订单号',width:150, fixed: 'left'}
                ,{field:'image_urls',title:'图片', width:120,templet:'#imageTEM',height:48}
                ,{field:'name',title:'{{ trans('goods.name') }}',width:250,templet:'#productTEM'}
                ,{field:'paypal_capture_id',title:'paypal', width:120,templet:'<div><a href="https://www.paypal.com/activity/payment/@{{ d.paypal_capture_id }}" target="_blank">@{{ d.paypal_capture_id }}</a></div>',height:48}
                ,{field:'tracking_number',title:'快递单号', width:120,height:48}
                ,{field:'date',title:'日期',width:120}
                ,{field:'status',title:'订单状态',width:200}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ $limit }}'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,done:function (res, curr, count) {
                element.init();
                merge(res);//合并单元格
            }
        });
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
                    if(data.code == 0)
                    {

                    }else{
                        layer.msg(data.msg);
                    }
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
                data.forEach(function(v){ data_id_obj[i] = v.id; i++});
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
                                data :  {'ids':data_id_obj,'_token' : "{!! csrf_token() !!}"},
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
            sync_update:function(){
                var checkStatus = table.checkStatus('fb-table')
                        ,data = checkStatus.data;
                var data_id_obj = {};
                var i = 0;
                data.forEach(function(v){ data_id_obj[i] = v.order_id; i++});
                if(data.length == 0)
                {
                    layer.msg('请选择要同步更新的数据', {
                        time: 2000 //2秒关闭（如果不配置，默认是3秒）
                    })
                    return ;
                }

                var load = layer.load();
                $.ajax({
                    url : main_url+'/sync_update',
                    data :  {'order_ids':data_id_obj,'_token' : "{!! csrf_token() !!}"},
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
        };
        $('.tabel-message .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });


        function merge(res) {
            var data = res.data;
            var mergeIndex = 0;//定位需要添加合并属性的行数
            var mark = 1; //这里涉及到简单的运算，mark是计算每次需要合并的格子数
            var _number = 1;//保持序号列数字递增
            var columsName = ['order_id'];//需要合并的列名称
            var columsIndex = [1];//需要合并的列索引值
            var mergeCondition = 'order_id';//需要合并的 首要条件  在这个前提下进行内容相同的合并
            var tdArrL = $('.layui-table-fixed-l > .layui-table-body').find("tr");//序号列左定位产生的table tr
            var tdArrR = $('.layui-table-fixed-r > .layui-table-body').find("tr");//操作列定右位产生的table tr

            for (var k = 0; k < columsName.length; k++) { //这里循环所有要合并的列
                var trArr = $(".layui-table-main>.layui-table").find("tr");//所有行
                for (var i = 1; i < res.data.length; i++) { //这里循环表格当前的数据

                    if (data[i][mergeCondition] === data[i-1][mergeCondition]) {
                        var tdCurArr = trArr.eq(i).find("td").eq(columsIndex[k]);//获取当前行的当前列
                        var tdPreArr = trArr.eq(mergeIndex).find("td").eq(columsIndex[k]);//获取相同列的第一列

                        if (data[i][columsName[k]] === data[i-1][columsName[k]]) { //后一行的值与前一行的值做比较，相同就需要合并
                            mark += 1;
                            tdPreArr.each(function () {//相同列的第一列增加rowspan属性
                                $(this).attr("rowspan", mark);
                            });
                            tdCurArr.each(function () {//当前行隐藏
                                $(this).css("display", "none");
                            });
                        }else {
                            mergeIndex = i;
                            mark = 1;//一旦前后两行的值不一样了，那么需要合并的格子数mark就需要重新计算
                        }
                    } else {
                        mergeIndex = i;
                        mark = 1;//一旦前后两行的值不一样了，那么需要合并的格子数mark就需要重新计算
                    }


                }
                mergeIndex = 0;
                mark = 1;
            }





            //操作左右定位列的表格
            $.each($("#fb-table").siblings('.layui-table-view').find('.layui-table-main>.layui-table').find("tr"),function (i,v) {
                if ($(v).find('td').eq(2).css('display') === 'none') {
                    tdArrL.eq(i).find('td').css('display','none');
                    tdArrR.eq(i).find('td').css('display','none');
                } else {
                    tdArrL.eq(i).find('td').find('.laytable-cell-numbers').html(_number++);
                    tdArrL.eq(i).find('td').css('height',$(v).find('td').eq(2)[0].clientHeight);
                    tdArrR.eq(i).find('td').css('height',$(v).find('td').eq(2)[0].clientHeight);

                }
            })



        }
        //合并结束
        laydate.render({
            elem: '#date' //指定元素
            ,type: 'date'
            ,range: '~'
        });
    });
</script>

