<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('order.index')); ?>"><cite><?php echo e(trans('order.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.edit')); ?><?php echo e(trans('order.name')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="table-Box">
                <div class="goods_list">
                    <table class="layui-table" lay-filter="cart" id="cart">
                        <thead>
                        <tr>
                            <th lay-data="{field:'id',width:80}">ID</th>
                            <th lay-data="{field:'goods_name',width:280}">商品名称</th>
                            <th lay-data="{field:'attribute_value'}">尺寸</th>
                            <th lay-data="{field:'purchase_price', edit: 'text'}"><?php echo e(trans('goods.label.purchase_price')); ?></th>
                            <th lay-data="{field:'selling_price', edit: 'text'}"><?php echo e(trans('goods.label.selling_price')); ?></th>
                            <th lay-data="{field:'number', edit: 'text'}">数量</th>
                        </tr>
                        </thead>
                        <tbody id="myTbody">
                            <?php $__currentLoopData = $order_goods_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $order_goods): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($order_goods->id); ?></td>
                                    <td><?php echo e($order_goods->goods_name); ?></td>
                                    <td><?php echo e($order_goods->attribute_value); ?></td>
                                    <td><?php echo e($order_goods->purchase_price); ?></td>
                                    <td><?php echo e($order_goods->selling_price); ?></td>
                                    <td><?php echo e($order_goods->number); ?></td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <table id="fb-table" class="layui-table"  lay-filter="fb-table">

                </table>
                <div class="fb-main-table">
                    <form class="layui-form" action="" lay-filter="fb-form">

                        <div class="layui-form-item fb-form-item">
                            <label class="layui-form-label">客户</label>
                            <div class="fb-form-item-box fb-clearfix">
                                <div class="layui-input-block">
                                    <?php $customerRepository = app('App\Repositories\Eloquent\CustomerRepository'); ?>
                                    <select name="customer_id" id="customer_id" lay-filter="" >
                                        <option value="">请选择客户</option>
                                        <?php $__currentLoopData = $customerRepository->orderBy('name','asc')->orderBy('id','desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($customer->id); ?>" <?php if($customer->id == $order->customer_id): ?> selected <?php endif; ?>><?php echo e($customer->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item2">
                            <label class="layui-form-label">地址</label>
                            <div class="fb-form-item-box" >
                                <div class="layui-input-block" style="width: 410px;">
                                    <textarea name="address" id="address" placeholder="请输入地址" class="layui-textarea"><?php echo $order->address; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item fb-form-item">
                            <label class="layui-form-label">业务员</label>
                            <div class="fb-form-item-box fb-clearfix">

                                <div class="layui-input-block">
                                    <?php $salesmanRepository = app('App\Repositories\Eloquent\SalesmanRepository'); ?>
                                    <select name="salesman_id" id="salesman_id" lay-filter="">
                                        <option value="">请选择业务员</option>
                                        <?php $__currentLoopData = $salesmanRepository->where('active',1)->orderBy('order','asc')->orderBy('id','desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $salesman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($salesman->id); ?>" <?php if($salesman->id == $order->salesman_id): ?> selected <?php endif; ?>><?php echo e($salesman->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
</script>

<script>

    layui.use(['element',"table",'form',"jquery"], function(){
        var form = layui.form;
        var table = layui.table;
        var upload = layui.upload;
        var $ = layui.$

        var main_url = "<?php echo e(guard_url('order')); ?>";
        table.init('cart', {
            cellMinWidth :'140'
            ,done:function(res, curr, count) {

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
            var ajax_data = {'_token':"<?php echo csrf_token(); ?>",customer_id:customer_id,address:address,salesman_id:salesman_id,'carts':tableData};
            var load = layer.load();
            $.ajax({
                url : "<?php echo e(guard_url('order/'.$order->id)); ?>",
                data : ajax_data,
                type : 'PUT',
                success : function (data) {
                    if(data.code == 0) {
                        window.location.href = "<?php echo e(guard_url('order')); ?>"
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