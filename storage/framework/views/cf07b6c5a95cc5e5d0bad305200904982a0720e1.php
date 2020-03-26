
<div class="layui-header">
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item" lay-unselect="">
            <a href="javascript:;"><img src="http://t.cn/RCzsdCq" class="layui-nav-img"><?php echo e(Auth::user()->name); ?></a>
            <dl class="layui-nav-child">
                <dd><a href="<?php echo e(guard_url('password')); ?>">修改信息</a></dd>
                <dd><a href="<?php echo e(guard_url('logout')); ?>">退出</a></dd>
            </dl>
        </li>
    </ul>
</div>
