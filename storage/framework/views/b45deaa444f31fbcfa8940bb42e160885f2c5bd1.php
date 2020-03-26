<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('customer.index')); ?>"><cite><?php echo e(trans('customer.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.add')); ?><?php echo e(trans('customer.name')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('customer')); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">业务员</label>
                        <div class="layui-input-block">
                            <select name="salesman_id" lay-filter="checkBox" lay-verify="required">
                                <option value="">请选择业务员</option>
                                <?php $__currentLoopData = $salesmen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $salesman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($salesman->id); ?>"><?php echo e($salesman->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">客户名称</label>

                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入客户名称" class="layui-input">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">IG号</label>

                        <div class="layui-input-block">
                            <input type="text" name="ig" lay-verify="title" autocomplete="off" placeholder="请输入IG号" class="layui-input">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">手机号</label>

                        <div class="layui-input-block">
                            <input type="text" name="mobile" autocomplete="off" placeholder="请输入手机号" class="layui-input">
                        </div>

                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">邮箱</label>

                        <div class="layui-input-block">
                            <input type="text" name="email" autocomplete="off" placeholder="请输入邮箱" class="layui-input">
                        </div>

                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">imessage</label>

                        <div class="layui-input-block">
                            <input type="text" name="imessage" autocomplete="off" placeholder="请输入imessage" class="layui-input">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">whatsapp</label>

                        <div class="layui-input-block">
                            <input type="text" name="whatsapp" autocomplete="off" placeholder="请输入whatsapp" class="layui-input">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">地址</label>

                        <div class="layui-input-block">
                            <textarea name="address" placeholder="请输入地址" class="layui-textarea"></textarea>
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">客户来源</label>

                        <div class="layui-input-block">
                            <select name="from" lay-filter="checkBox">
                                <?php $__currentLoopData = config('model.customer.customer.from'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $from): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($from); ?>"><?php echo e($from); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                </form>
            </div>

        </div>
    </div>
</div>

