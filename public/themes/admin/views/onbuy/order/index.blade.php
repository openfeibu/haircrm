<style>
    .layui-table-cell{
        height: auto !important;
    }
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
                    <button class="layui-btn layui-btn-warm " type="button" data-type="import_express_excel" data-events="import_express_excel">导入 快递信息</button>
                    <button class="layui-btn layui-btn-primary " type="button" data-type="export_yanwen_excel" data-events="export_yanwen_excel">下载 燕文Excel</button>
                    <!--<button class="layui-btn layui-btn-warm " data-type="mark_purchase" data-events="mark_purchase">标记为已拿货</button>-->
                    <!--<button class="layui-btn layui-btn-danger " data-type="del" data-events="del">{{ trans('app.delete') }}</button>-->
                </div>
            </div>
            <div class="tabel-message">
                <div class="layui-inline">
                    <label class="layui-form-label">状态</label>
                    <select name="onbuy_orders.status" class="search_key layui-select">
                        <option value="">全部</option>
                        @foreach(config('model.onbuy.order.status') as $key => $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">下单日期 *</label>
                    <div class="layui-input-block">
                        <input type="text" name="onbuy_orders.date" id="date" class="layui-input search_key">
                    </div>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="onbuy_orders.order_id" id="demoReload" placeholder="订单ID" autocomplete="off">
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="onbuy_order_products.sku" id="demoReload" placeholder="sku" autocomplete="off" value="{{ $search['onbuy_order_products.sku'] ?? '' }}">
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
<div class="tabel-message" id="import_express_content" style="display: none;">
    <form class="form-horizontal" method="POST" action="{{ guard_url('onbuy/order/import/express') }}" enctype="multipart/form-data"  id="import_express_form" lay-filter="import_express_form">
        <div class="layui-row layui-col-space10">
            <div class="tabel-btn layui-col-md12">
                <button class="layui-btn layui-btn-warm "><a href="{{url('image/original/system/onbuy_order_import_express_template.xlsx')}}">下载模板</a></button>
            </div>
            <div class="tabel-btn layui-col-md12">
                {{ csrf_field() }}
                <div class="input-file" >
                    选择文件
                    <input id="file" type="file" class="form-control" name="file" required>
                </div>
                <label class="fileText">未选中文件</label>
                <span class="layui-word-aux des_content">（注意：请严格按照模板的格式上传Excel！）</span>
            </div>
        </div>
    </form>
</div>
<script type="text/html" id="barDemo">
    <p>
        <span>@{{ d.status }}</span>
    </p>
    <!--<p>
        <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    </p>-->
</script>

<script type="text/html" id="orderIdTEM">

    <div>
        <p> <a href="https://seller.onbuy.com/orders/@{{ d.onbuy_internal_reference }}/" target="_blank">@{{ d.order_id }}</a></p>
        <p> 英:@{{ d.date }} </p>
        <p> 中:@{{ d.ch_date }} </p>

    </div>
</script>

<script type="text/html" id="imageTEM">
    <a href="@{{ d.product_url }}" target="_blank"><img src="@{{d.image_urls.thumb}}" alt="" height="58"></a>
</script>


<script type="text/html" id="productTEM">
    <div>
        @{{#  layui.each(d.order_products, function(index, item){ }}
        <p><a href="@{{ item.product_url }}" target="_blank"><img src="@{{item.image_urls.thumb}}" alt="" height="58"></a></p>
        <p> <a href="@{{ item.product_url }}" target="_blank">@{{ item.name }}</a></p>
        <p> sku: @{{ item.sku }} </p>
        <p> 单价: £@{{ item.unit_price }} * 数量: @{{ item.quantity }} = £@{{ item.total_price }}</p>
        <p> 采购价:￥@{{ item.purchase_price }} </p>
        <p> 发货: @{{ item.expected_dispatch_date }}</p>
        @{{#  }); }}
    </div>
</script>

<script type="text/html" id="priceDetailTEM">
    <div>
        <p> 总价: £@{{ d.price_total }}</p>
        <p> 平台费: £@{{ d.fee_total_fee_including_vat }}</p>
        <p> 税费: £@{{ d.tax_total ?? 0 }}</p>
        <p> 采购价: ￥@{{ d.total_purchase_price }}</p>
        <p> 预计运费: ￥@{{ d.freight_expect }} </p>
        <p> 预计总成本: ￥@{{ d.cost }}</p>
        <p> 预计利润:
            @{{# if(parseFloat(d.profit_expect) >= 0){ }}
                <span>

            @{{# }else{ }}
                <span style="color:red">
            @{{# } }}
                @{{ d.profit_expect }}</span>
        </p>
    </div>
</script>
<script type="text/html" id="costTEM">
    <div>

        <p> 采购价: @{{ d.total_purchase_price }}</p>
        <p> 运费: @{{ d.freight_expect }} </p>
        <p> 总成本: @{{ d.cost }}</p>

    </div>
</script>
<script type="text/html" id="profitExpectTEM">
    <div>
        @{{# if(parseFloat(d.profit_expect) >= 0){ }}
        <span>

        @{{# }else{ }}
        <span style="color:red">
        @{{# } }}
        @{{ d.profit_expect }}</span>

    </div>
</script>

<script type="text/html" id="trackingTEM">

    <div>
        @{{# if(d.tracking_url){ }}
        <p> <a href="@{{ d.tracking_url }}" target="_blank">@{{ d.tracking_number }}</a></p>
        <p> 物流公司: @{{ d.tracking_supplier_name }} </p>
        @{{# } }}
    </div>
</script>


<script type="text/html" id="customerTEM">

    <div>
        <p> @{{ d.buyer_name }}</p>
        <p> @{{ d.buyer_email }} </p>
        <p> @{{ d.buyer_phone }} </p>
    </div>
</script>

<script type="text/html" id="deliveryAddressTEM">

    <div>
        <p> 名字 @{{ d.delivery_address.name }}</p>
        <p> 行1: @{{  d.delivery_address.line_1 }} </p>
        <p> 行2: @{{  d.delivery_address.line_2 }} </p>
        <p> 行3: @{{  d.delivery_address.line_3 }} </p>
        <p> 乡镇: @{{  d.delivery_address.town }} </p>
        <p> 城市: @{{  d.delivery_address.county }} </p>
        <p> 邮编: @{{  d.delivery_address.postcode }} </p>
        <p> 国家: @{{  d.delivery_address.country }}  @{{  d.delivery_address.country_code }}</p>
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
        var where = {};
        $(".search_key").each(function(){
            var name = $(this).attr('name');
            where["search["+name+"]"] = $(this).val();
        });
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
                ,{field:'order_id',title:'订单号',width:130, fixed: 'left',templet:'#orderIdTEM'}
                ,{field:'goods',title:'产品', width:250,height:48,templet:'#productTEM'}
                ,{field:'customer',title:'客户', width:150,height:48,templet:'#customerTEM'}
                ,{field:'delivery_address',title:'地址', width:200,height:48,templet:'#deliveryAddressTEM'}
                ,{field:'paypal_capture_id',title:'paypal', width:120,templet:'<div><a href="https://www.paypal.com/activity/payment/@{{ d.paypal_capture_id }}" target="_blank">@{{ d.paypal_capture_id }}</a></div>',height:48}
                ,{field:'tracking_number',title:'快递单号', width:120,height:48}
                ,{field:'price_detail',title:'费用明细', width:150,height:48,templet:'#priceDetailTEM'}
                ,{field:'profit_expect',title:'预计利润￥', width:120,height:48,templet:'#profitExpectTEM', totalRow: true}
//                ,{field:'price_total',title:'总价£', width:90,height:48}
//                ,{field:'fee_total_fee_including_vat',title:'平台费£', width:90,height:48}
//                ,{field:'tax_total',title:'税费£', width:90,height:48}
//                ,{field:'cost_expect',title:'预计成本￥', width:120,height:48,templet:'#costTEM'}

                ,{field:'date',title:'日期',width:120}
                ,{field:'status',title:'订单状态',width:160, fixed: 'right'}
                //,{field:'score',title:'{{ trans('app.actions') }}', width:150, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ $limit }}'
            ,where: where
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,totalRow: true //开启合计行
            ,done:function (res, curr, count) {
                element.init();
                //merge(res);//合并单元格
                //设置工具栏表头高度
                $(".layui-table-header").eq(1).find("table").height($(".layui-table-header").eq(0).height()+1);
                $(".layui-table-header").eq(2).find("table").height($(".layui-table-header").eq(0).height()+1);
                //设置工具栏按钮栏高度
                $(".layui-table").eq(1).find("tr").each(function(index,ele){
                    $(".layui-table-body").eq(1).find("tr").eq(index).height($(ele).height());
                });

                // 该方法用于解决,使用fixed固定列后,行高和其他列不一致的问题
                $(".layui-table-main  tr").each(function (index, val) {
                    console.log($(val).html());
                    $($(".layui-table-fixed .layui-table-body tbody tr")[index]).height($(val).height());
                });
                $(".layui-table-fixed-r  tr").each(function (index, val) {
                    $($(".layui-table-fixed-r .layui-table-body tbody tr")[index]).height($($(".layui-table-main  tr")[index]).height());
                });
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
            export_yanwen_excel:function () {
                var checkStatus = table.checkStatus('fb-table')
                        ,data = checkStatus.data;
                var data_id_obj = {};
                var i = 0;
                var url = '{{ guard_url('onbuy/order/export/express_yanwen') }}';
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
            },
            import_express_excel:function () {
                layer.open({
                    type: 1,
                    shade: false,
                    title: '导入 快递信息', //不显示标题
                    area: ['420px', '240px'], //宽高
                    content: $('#import_express_content'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {
                        var load = layer.load();
                        var fileFlag = false;

                        $("input[name='file']").each(function(){
                            if($(this).val()!="") {
                                fileFlag = true;
                            }
                        });
                        if(!fileFlag) {
                            layer.msg("请选择文件");
                            return false;
                        }

                        layer.msg('上传中', {
                            icon: 16
                            ,shade: 0.01
                            ,time:0
                        });
                        $("#import_express_form").submit();
                        /*
                        form.submit("import_express_form", function(data){
                            // 回调函数返回结果跟上述 submit 事件完全一致
                            // var field = data.field;
                            // do something
                        });
                        */
                    }
                });
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
        $(".input-file input").on('change', function( e ){
            //e.currentTarget.files 是一个数组，如果支持多个文件，则需要遍历
            var name = e.currentTarget.files[0].name;
            $(".fileText").text(name)
        });
    });
</script>

