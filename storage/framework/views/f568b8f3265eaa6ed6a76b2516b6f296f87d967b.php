<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(route('category.index')); ?>"><cite><?php echo e(trans('category.title')); ?></cite></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('app.add')); ?><?php echo e(trans('category.name')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="<?php echo e(guard_url('category/'.$category->id)); ?>" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">上级</label>

                        <div class="layui-input-block">
                            <p class="input-p"><?php echo e($category->parent_names); ?></p>
                        </div>

                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('supplier.name')); ?></label>

                        <div class="layui-input-block">
                            <?php $supplierRepository = app('App\Repositories\Eloquent\SupplierRepository'); ?>
                            <select name="supplier_id" id="supplier_id">
                                <option value="0">默认上级</option>
                                <?php $__currentLoopData = $supplierRepository->suppliers(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($supplier['id']); ?>" <?php if($supplier->id == $category->supplier_id): ?> selected <?php endif; ?>><?php echo $supplier['name']; ?>(<?php echo e($supplier->code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="layui-form-mid layui-word-aux">非必选，如 Best virgin hair - Lace 分类下，选了A仓，则该分类下的所有子分类默认为 A仓（除非子类选了其他仓）</div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label"><?php echo e(trans('category.label.name')); ?></label>

                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo e($category->name); ?>">
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="demo1">立即提交</button>
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

</script>