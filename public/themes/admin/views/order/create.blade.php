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
                            <input type="text" name="category_id" id="category_tree" lay-verify="tree" autocomplete="off" placeholder="请选择分类(加载中)" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2 " id="goods_attributes" style="display:none;">
                        <label class="layui-form-label">选择尺寸 *</label>
                        <div class="fb-form-item-box fb-clearfix">
                            <div class="layui-input-block layui-input-line">
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
                            <th lay-data="{field:'attribute_value'}" class="attribute_name">属性</th>
                            <th lay-data="{field:'purchase_price', edit: 'text'}">{{ trans('goods.label.purchase_price') }}</th>
                            <th lay-data="{field:'selling_price', edit: 'text'}">{{ trans('goods.label.selling_price') }}</th>
                            <th lay-data="{field:'weight', edit: 'text'}">{{ trans('order.label.weight') }}</th>
                            <th lay-data="{field:'number', edit: 'text'}">数量</th>
                            <th lay-data="{field:'remark', edit: 'text'}">{{ trans('app.remark') }}</th>
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
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('order.label.weight') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="weight">0</p>
                            </div>
                        </div>
                        <div class="layui-form-item" id="freight_content">
                            <label class="layui-form-label">{{ trans('order.label.freight') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="freight">0</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('goods.label.purchase_price') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="purchase_price">0</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('goods.label.selling_price') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="selling_price">0</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('order.label.paypal_fee') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="paypal_fee">0</p>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">{{ trans('order.label.total') }}</label>
                            <div class="layui-input-inline">
                                <p class="input-p" id="total">0</p>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item">
                            <label class="layui-form-label">客户 *</label>
                            <div class="fb-form-item-box fb-clearfix">
                                <div class="layui-input-block">
                                    @inject('customerRepository','App\Repositories\Eloquent\CustomerRepository')
                                    <select name="customer_id" id="customer_id" lay-filter="customer" lay-search>
                                        <option value="">请选择客户</option>
                                        @foreach($customerRepository->orderBy('name','asc')->orderBy('id','desc')->get() as $key => $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item2">
                            <label class="layui-form-label">{{ trans('order.label.address') }} *</label>
                            <div class="fb-form-item-box" >
                                <div class="layui-input-block" style="width: 410px;">
                                    <textarea name="address" id="address" placeholder="请输入{{ trans('order.label.address') }}" class="layui-textarea"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item">
                            <label class="layui-form-label">业务员 *</label>
                            <div class="fb-form-item-box fb-clearfix">

                                <div class="layui-input-block">
                                    @inject('salesmanRepository','App\Repositories\Eloquent\SalesmanRepository')
                                    <select name="salesman_id" id="salesman_id" lay-filter="" lay-search>
                                        @foreach($salesmanRepository->where('active',1)->orderBy('order','asc')->orderBy('id','desc')->get() as $key => $salesman)
                                        <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                        @endforeach
                                    </select>
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

@include('order/category_tree_js')
@include('order/handle_cart')

<script>

    layui.use(['element',"table",'form',"jquery"], function(){
        var form = layui.form;
        var table = layui.table;
        var upload = layui.upload;
        var $ = layui.$
        table.init('cart', {
            cellMinWidth :'140'
            ,page:false
            ,limit:99
            ,done:function(res, curr, count) {

            }
        });
        table.on('tool(cart)', function(obj){
            if(obj.event === 'del'){
                obj.del();
                handle_number();
            }
        });
        //监听提交
        form.on('submit(submit_btn)', function(data){
            var tableData = layui.table.cache.cart;
            var customer_id = $("#customer_id").val();
            var address = $("#address").val();
            var salesman_id = $('#salesman_id').val();
            if(!tableData)
            {
                layer.msg("请先添加订单产品");
                return false;
            }
            if(!customer_id || !address || !salesman_id)
            {
                layer.msg("客户、地址、业务员必填");
                return false;
            }
            var ajax_data = {'_token':"{!! csrf_token() !!}",customer_id:customer_id,address:address,salesman_id:salesman_id,'carts':tableData};
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
                        layer.msg(data.msg);
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
