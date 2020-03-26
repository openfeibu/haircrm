<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('goods.index')); ?>"><cite><?php echo e(trans('goods.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.edit')); ?><?php echo e(trans('goods.name')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('goods/'.$goods->id)); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('goods.label.name')); ?></label>
                        <div class="layui-input-block">
                            <p class="input-p"><?php echo e($goods->name); ?></p>
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">选择尺寸</label>
                        <div class="fb-form-item-box fb-clearfix">
                            <?php $__currentLoopData = $attribute_values; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $attribute_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="layui-input-block">
                                <?php if(in_array($attribute_value['id'],$goods->attr_value_id_arr)): ?>
                                <input type="checkbox" name="attribute_value[<?php echo e($attribute_value['id']); ?>]" lay-skin="primary" title="<?php echo e($attribute_value['value']); ?>" checked="">
                                <input type="text" name="purchase_price[<?php echo e($attribute_value['id']); ?>]" lay-verify="title" autocomplete="off" placeholder="<?php echo e(trans('goods.label.purchase_price')); ?>" class="layui-input minInput" value="<?php echo e($goods_attribute_values[$attribute_value['id']]['purchase_price']); ?>">
                                <input type="text" name="selling_price[<?php echo e($attribute_value['id']); ?>]" lay-verify="title" autocomplete="off" placeholder="<?php echo e(trans('goods.label.selling_price')); ?>" class="layui-input minInput" value="<?php echo e($goods_attribute_values[$attribute_value['id']]['selling_price']); ?>">
                                <?php else: ?>
                                    <input type="checkbox" name="attribute_value[<?php echo e($attribute_value['id']); ?>]" lay-skin="primary" title="<?php echo e($attribute_value['value']); ?>" checked="">
                                    <input type="text" name="purchase_price[<?php echo e($attribute_value['id']); ?>]" lay-verify="title" autocomplete="off" placeholder="<?php echo e(trans('goods.label.purchase_price')); ?>" class="layui-input minInput">
                                    <input type="text" name="selling_price[<?php echo e($attribute_value['id']); ?>]" lay-verify="title" autocomplete="off" placeholder="<?php echo e(trans('goods.label.selling_price')); ?>" class="layui-input minInput">
                                <?php endif; ?>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>

                    <?php echo Form::token(); ?>

                    <input type="hidden" name="_method" value="PUT">
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>



