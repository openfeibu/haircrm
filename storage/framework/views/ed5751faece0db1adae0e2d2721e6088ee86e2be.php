<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(route('home')); ?>">主页</a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans("role.name")); ?></cite></a><span lay-separator="">/</span>
            <a><cite>添加<?php echo e(trans("role.name")); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('role/'.$role->id)); ?>" method="POST" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo trans('role.label.name'); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" lay-verify="title" autocomplete="off" placeholder="请输入<?php echo trans('role.label.name'); ?>" class="layui-input" value="<?php echo e($role->name); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><?php echo trans('role.label.slug'); ?></label>
                        <div class="layui-input-inline">
                            <input type="text" name="slug" placeholder="请输入<?php echo trans('role.label.slug'); ?>" autocomplete="off" class="layui-input" value="<?php echo e($role->slug); ?>">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">权限</label>
                        <div class="layui-col-md10">
                            <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f_key => $father): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="top-permission layui-col-md10">
                                    <a href="javascript:;" class="display-sub-permission-toggle">
                                        <i class="layui-icon layui-icon-down"></i>
                                    </a>
                                    <input type="checkbox" name="permissions[]" value="<?php echo e($father->id); ?>" class="top-permission-checkbox" lay-skin="primary" title="<?php echo e($father->name); ?>" lay-filter="top-permission-checkbox" <?php echo e((!$role->hasPermission($father->id)) ? : 'checked'); ?>>
                                </div>
                                <div class="sub-permissions layui-col-md9 layui-col-md-offset1">
                                    <?php if(isset($father->sub)): ?>
                                        <?php $__currentLoopData = $father->sub; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="layui-col-sm3">
                                                <input type="checkbox" name="permissions[]" value="<?php echo e($item->id); ?>" class="sub-permission-checkbox" lay-skin="primary" title="<?php echo e($item->name); ?>" lay-filter="sub-permission-checkbox" <?php echo e((!$role->hasPermission($item->id)) ? : 'checked'); ?>>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    <?php echo Form::token(); ?>

                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    layui.use(['jquery','form'], function(){
        var form = layui.form;
        form.render();
        var $ = layui.$;

        $(".display-sub-permission-toggle").click(function(){
            if($(this).parents('.top-permission').next('.sub-permissions').css("display") == 'none')
            {
                $(this).children('i').removeClass('layui-icon-right').addClass('layui-icon-down').parents('.top-permission').next('.sub-permissions').show();
            }else{
                $(this).children('i').removeClass('layui-icon-down').addClass('layui-icon-right').parents('.top-permission').next('.sub-permissions').hide();
            }
        });

        form.on('checkbox(top-permission-checkbox)', function(data){
            if(data.elem.checked){
                $(this).parents('.top-permission').next('.sub-permissions').find('.sub-permission-checkbox').prop('checked', true);
            }else{
                $(this).parents('.top-permission').next('.sub-permissions').find('.sub-permission-checkbox').prop('checked', false);
            }
            form.render();
        });
        form.on('checkbox(sub-permission-checkbox)', function(data){
            if(data.elem.checked){
                $(this).parents('.sub-permissions').prev('.top-permission').find('.top-permission-checkbox').prop('checked', true);
            }else{
                var sub_check = false; //没有子选择
                $(this).parents('.sub-permissions').find('.sub-permission-checkbox').each(function(){
                    if($(this).prop('checked')){
                        sub_check = true;
                        return false;
                    }
                });
                if(!sub_check){
                    $(this).parents('.sub-permissions').prev('.top-permission').find('.top-permission-checkbox').prop('checked', false);
                }
            }
            form.render();
        });

    });
</script>
<script>

</script>