<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="logo"><?php echo e(trans('app.site_name')); ?></div>
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            
            <?php $permissionPresenter = app('App\Repositories\Presenter\PermissionPresenter'); ?>

            <?php echo $permissionPresenter->menus(); ?>

        </ul>

    </div>
</div>

<!-- 左侧菜单结束 -->

