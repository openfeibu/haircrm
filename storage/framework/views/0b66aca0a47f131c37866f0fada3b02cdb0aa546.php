<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('new_customer.index')); ?>"><cite><?php echo e(trans('new_customer.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.add')); ?> <?php echo e(trans('new_customer.name')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('new_customer/'.$new_customer->id)); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">业务员</label>
                        <div class="layui-input-block">
                            <select name="salesman_id" lay-filter="checkBox" lay-verify="required">
                                <option value="">请选择业务员</option>
                                <?php $__currentLoopData = $salesmen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $salesman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($salesman->id); ?>" <?php if($salesman->id == $new_customer->salesman_id): ?> selected <?php endif; ?>><?php echo e($salesman->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.mark')); ?></label>
                        <div class="layui-input-block">
                            <select name="mark" lay-filter="checkBox" lay-verify="required">
                                <?php $__currentLoopData = trans('new_customer.mark'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $mark): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>" <?php if($key == $new_customer->mark): ?> selected <?php endif; ?>><?php echo e($mark); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.company_name')); ?></label>

                        <div class="layui-input-block">
                            <input type="text" name="company_name" lay-verify="required" autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.company_name')); ?>" class="layui-input" value="<?php echo e($new_customer->company_name); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.company_website')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="company_website"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.company_website')); ?>" class="layui-input" value="<?php echo e($new_customer->company_website); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.nickname')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="nickname"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.nickname')); ?>" class="layui-input" value="<?php echo e($new_customer->nickname); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.email')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="email"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.email')); ?>" class="layui-input" value="<?php echo e($new_customer->email); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.mobile')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="mobile"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.mobile')); ?>" class="layui-input" value="<?php echo e($new_customer->mobile); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.imessage')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="imessage"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.imessage')); ?>" class="layui-input" value="<?php echo e($new_customer->imessage); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.whatsapp')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="whatsapp"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.whatsapp')); ?>" class="layui-input" value="<?php echo e($new_customer->whatsapp); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.main_product')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="main_product"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.main_product')); ?>" class="layui-input" value="<?php echo e($new_customer->main_product); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.ig')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="ig"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.ig')); ?>" class="layui-input" value="<?php echo e($new_customer->ig); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.ig_follower_count')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="ig_follower_count"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.ig_follower_count')); ?>" class="layui-input" value="<?php echo e($new_customer->ig_follower_count); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.ig_sec')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="ig_sec"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.ig_sec')); ?>" class="layui-input" value="<?php echo e($new_customer->ig_sec); ?>">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.facebook')); ?></label>
                        <div class="layui-input-block">
                            <input type="text" name="facebook"  autocomplete="off" placeholder="请输入 <?php echo e(trans('new_customer.label.facebook')); ?>" class="layui-input" value="<?php echo e($new_customer->facebook); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('new_customer.label.remark')); ?></label>
                        <div class="layui-input-block">
                            <textarea name="remark" id="remark" placeholder="请输入 <?php echo e(trans('new_customer.label.remark')); ?>" class="layui-textarea"></textarea>
                        </div>
                    </div>
                    <input type="hidden" name="_method" value="PUT">
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

