
<div class="layui-header">
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item" lay-unselect="">
            <a lay-text="更新缓存" class="cache" >
                更新缓存
            </a>
        </li>
        <li class="layui-nav-item" lay-unselect="">
            <a href="javascript:;"><img src="http://t.cn/RCzsdCq" class="layui-nav-img">{{ Auth::user()->name }}</a>
            <dl class="layui-nav-child">
                <dd><a href="{{ guard_url('password') }}">修改信息</a></dd>
                <dd><a href="{{ guard_url('logout') }}">退出</a></dd>
            </dl>
        </li>
    </ul>
</div>
