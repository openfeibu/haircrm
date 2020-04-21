<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('order.index') }}"><cite>{{ trans('order.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}{{ trans('order.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="table-Box">
                <div class="goods_list">
                    <table class="layui-table" lay-filter="cart" id="cart">
                        <thead>
                        <tr>
                            <th lay-data="{field:'id',width:80}">ID</th>
                            <th lay-data="{field:'goods_name',width:280}">商品名称</th>
                            <th lay-data="{field:'attribute_value'}">尺寸</th>
                            <th lay-data="{field:'selling_price', edit: 'text'}">{{ trans('goods.label.selling_price') }}</th>
                            <th lay-data="{field:'weight'}">{{ trans('order.label.weight') }}</th>
                            <th lay-data="{field:'number', edit: 'text'}">数量</th>
                            <th lay-data="{field:'freight_category_id',  hide:true}">freight_category_id</th>
                        </tr>
                        </thead>
                        <tbody id="myTbody">
                            @foreach($order_goods_list as $key => $order_goods)
                                <tr>
                                    <td>{{ $order_goods->id }}</td>
                                    <td>{{ $order_goods->goods_name }}</td>
                                    <td>{{ $order_goods->attribute_value }}</td>
                                    <td>{{ $order_goods->selling_price }}</td>
                                    <td>{{ $order_goods->weight }}</td>
                                    <td>{{ $order_goods->number }}</td>
                                    <td>{{ $order_goods->freight_category_id }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <table id="fb-table" class="layui-table"  lay-filter="fb-table">

                </table>
                <div class="fb-main-table">
                    <form class="layui-form" action="" lay-filter="fb-form">
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('order.label.weight') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="weight">{{ $order['weight'] }}</p>
                            </div>
                        </div>
                        <div class="layui-form-item" id="freight_content">
                            <label class="layui-form-label">{{ trans('order.label.freight') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="freight">{{ $order['freight'] }}</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('goods.label.selling_price') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="selling_price">{{ $order['selling_price'] }}</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('order.label.paypal_fee') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="paypal_fee">{{ $order['paypal_fee'] }}</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('order.label.total') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="total">{{ $order['total'] }}</p>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item">
                            <label class="layui-form-label">客户</label>
                            <div class="fb-form-item-box fb-clearfix">
                                <div class="layui-input-block">
                                    @inject('customerRepository','App\Repositories\Eloquent\CustomerRepository')
                                    <select name="customer_id" id="customer_id" lay-filter="customer" >
                                        <option value="">请选择客户</option>
                                        @foreach($customerRepository->getSalesmanCustomers(Auth::user()->id) as $key => $customer)
                                            <option value="{{ $customer->id }}" @if($customer->id == $order->customer_id) selected @endif>{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item2">
                            <label class="layui-form-label">地址</label>
                            <div class="fb-form-item-box" >
                                <div class="layui-input-block" style="width: 410px;">
                                    <textarea name="address" id="address" placeholder="请输入地址" class="layui-textarea">{!! $order->address !!}</textarea>
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

@include('order/handle_cart')

<script>

    layui.use(['element',"table",'form',"jquery"], function(){
        var form = layui.form;
        var table = layui.table;
        var upload = layui.upload;
        var $ = layui.$

        var main_url = "{{guard_url('order')}}";
        table.init('cart', {
            cellMinWidth :'140'
            ,done:function(res, curr, count) {

            }
        });

        //监听提交
        form.on('submit(submit_btn)', function(data){
            var tableData = layui.table.cache.cart;
            var customer_id = $("#customer_id").val();
            var address = $("#address").val();
            if(!tableData)
            {
                layer.msg("请先添加订单产品");
            }
            if(!customer_id || !address)
            {
                layer.msg("客户、地址、业务员必填");
                return false;
            }
            var ajax_data = {'_token':"{!! csrf_token() !!}",customer_id:customer_id,address:address,'carts':tableData};
            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('order/'.$order->id) }}",
                data : ajax_data,
                type : 'PUT',
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


    });

</script>