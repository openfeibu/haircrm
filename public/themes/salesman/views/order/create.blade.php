<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('order.index') }}"><cite>{{ trans('order.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}{{ trans('order.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('order')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">选择分类 *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="category_id" id="category_tree"lay-verify="tree" autocomplete="off" placeholder="请选择分类(加载中)" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2 " id="goods_attributes" style="display:none;">
                        <label class="layui-form-label">选择尺寸 *</label>
                        <div class="fb-form-item-box fb-clearfix">
                            <div class="layui-input-block">
                                <p class="input-p a-select" id="size">

                                </p>
                            </div>
                        </div>
                    </div>

                    {!!Form::token()!!}

                </form>
            </div>
            <div class="table-Box">
                <div class="goods_list">
                    <table class="layui-table" lay-filter="cart" id="cart">
                        <thead>
                        <tr>
                            <th lay-data="{field:'id',width:80}">ID</th>
                            <th lay-data="{field:'goods_name',width:280}">商品名称</th>
                            <th lay-data="{field:'attribute_value'}">属性</th>
                            <th lay-data="{field:'selling_price', edit: 'text'}">{{ trans('goods.label.selling_price') }}</th>
                            <th lay-data="{field:'number', edit: 'text'}">数量</th>
                            <th lay-data="{field:'score',title:'{{ trans('app.actions') }}', width:120, align: 'right',toolbar:'#barDemo'}">操作</th>
                        </tr>
                        </thead>
                        <tbody id="myTbody">

                        </tbody>
                    </table>
                </div>
                <table id="fb-table" class="layui-table"  lay-filter="fb-table">

                </table>
                <div class="fb-main-table">
                    <form class="layui-form" action="" lay-filter="fb-form">

                        <div class="layui-form-item fb-form-item">
                            <label class="layui-form-label">客户 *</label>
                            <div class="fb-form-item-box fb-clearfix">
                                <div class="layui-input-block">
                                    @inject('customerRepository','App\Repositories\Eloquent\CustomerRepository')
                                    <select name="customer_id" id="customer_id" lay-filter="customer" >
                                        <option value="">请选择客户</option>
                                        @foreach($customerRepository->getSalesmanCustomers(Auth::user()->id) as $key => $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item2">
                            <label class="layui-form-label">地址 *</label>
                            <div class="fb-form-item-box" >
                                <div class="layui-input-block" style="width: 410px;">
                                    <textarea name="address" id="address" placeholder="请输入地址" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="layui-form-item" style="text-align: center;margin-top: 20px;">
                    <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="submit_btn">确认订单</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>


<script>
    var sizes = {};
    layui.use(['treeSelect', 'form', 'layer'], function () {
        var treeSelect= layui.treeSelect,
                form = layui.form,
                $ = layui.jquery,
                layer = layui.layer;

        treeSelect.render({
            elem: '#category_tree',
            data: '/categories_tree',
            headers: {},
            type: 'get',
            // 占位符
            placeholder: '请选择分类',
            //多选
            showCheckbox: false,
            //连线
            showLine: true,
            //选中节点(依赖于 showCheckbox 以及 key 参数)。
            //checked: [11, 12],
            //展开节点(依赖于 key 参数)
            spread: [1],
            // 点击回调
            click: function(obj){
                sizes = {};
                $("#goods_attributes").hide();
                var load = layer.load();
                var ajax_data = {};
                ajax_data['_token'] = "{!! csrf_token() !!}";
                ajax_data['category_id'] = obj.id;
                $.ajax({
                    url : "{{ guard_url('category_goods') }}",
                    data : ajax_data,
                    type : 'get',
                    success : function (data) {
                        layer.close(load);
                        if(data.code != 0)
                        {
                            layer.msg(data.message);
                        }
                        if(!$.isEmptyObject(data.data.goods))
                        {
                            var goods = data.data.goods;


                            $('#size').html("");
                            var html = '';
                            $.each(data.data.goods_list,function (i,val) {
                                //val['goods_name'] = val['goods_name'];
                                sizes[i] = val;
                                html += "<a href='javascript:;' class='layui-btn layui-btn-warm' i="+i+">"+val.attribute_value+"</a>";
                                console.log(sizes);
                            })
                            if(goods.attribute_id) {
                                $("#goods_attributes").show();
                                $('#size').html(html);
                            } else{
                                $.onNodeClick(sizes[0]);
                            }
                        }else{
                            layer.msg("该分类下未发现产品，请先添加产品");
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        layer.msg('服务器出错');
                    }
                });
            },
            // 加载完成后的回调函数
            success: function (d) {
                console.log(d);
            }
        });
    });
</script>

<script>

    layui.use(['element',"table",'form',"jquery"], function(){
        var form = layui.form;
        var table = layui.table;
        var upload = layui.upload;
        var $ = layui.$
        table.init('cart', {
            cellMinWidth :'140'
            ,done:function(res, curr, count) {

            }
        });
        table.on('tool(cart)', function(obj){
            if(obj.event === 'del'){
                obj.del();
            }
        });
        form.on('checkbox', function(obj){
            var check = $(obj.othis).hasClass("layui-form-checked");
            if(check){
                $(obj.othis).parents(".layui-input-block").find(".numInput").show()
            }else{
                $(obj.othis).parents(".layui-input-block").find(".numInput").hide()

            }
        });

        $.onNodeClick = function (node) {
            var tableData = layui.table.cache.cart;
            var flag = false;
            if(tableData != null)
            {
                for (var i=0;i<tableData.length;i++)
                {
                    if(tableData[i]['list_id'] == node.list_id)
                    {
                        var number = tableData[i]['number'];
                        number++;
                        tableData[i]['number'] = number;
                        flag = true;
                    }else{
                        flag = flag ? flag : false;
                    }
                }
                if(flag == true)
                {
                    table.reload("cart",{data:tableData});
                }else{
                    appendTbody(node)
                }
            }else{
                appendTbody(node)
            }
        }
        function appendTbody(node) {
            var data = [];
            var tableData = layui.table.cache.cart;
            if(tableData != null)
            {
                for (var i=0;i<tableData.length;i++)
                {
                    data.push(tableData[i]);
                }
            }
            node.number = 1;
            data.push(node);
            table.reload("cart",{data:data});
        }
        $("body").on('click','#size a',function () {
            var i = $(this).attr('i');
            var node = sizes[i];
            $.onNodeClick(node);
        })

        //监听提交
        form.on('submit(submit_btn)', function(data){
            var tableData = layui.table.cache.cart;
            var customer_id = $("#customer_id").val();
            var address = $("#address").val();
            if(!tableData)
            {
                layer.msg("请先添加订单产品");
                return false;
            }
            if(!customer_id || !address)
            {
                layer.msg("客户、地址必填");
                return false;
            }
            var ajax_data = {'_token':"{!! csrf_token() !!}",customer_id:customer_id,address:address,'carts':tableData};
            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('order') }}",
                data : ajax_data,
                type : 'POST',
                success : function (data) {
                    if(data.code == 0) {
                        window.location.href = "{{ guard_url('order') }}"
                    }else{
                        layer.close(load);
                        layer.msg(data.message);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
            return false;
        });

        form.on('select(customer)', function(data){
            var customer_id = data.value;
            if(!customer_id)
            {
                return false;
            }
            var ajax_data = {'_token':"{!! csrf_token() !!}",id:customer_id};
            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('get_customer') }}",
                data : ajax_data,
                type : 'get',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0) {
                        $("#address").text(data.data.address ? data.data.address : '')
                    }else{
                        layer.msg(data.message);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
        });
    });

</script>
