<div class="layui-side layui-side-menu">
    <div class="layui-side-scroll">
        <div class="logo"><img src="{{ url('/image/original').setting('logo') }}" /></div>
        <ul class="layui-nav layui-nav-tree" lay-filter="test">
            {{--{!!Menu::menu('admin', 'menu.menu.admin')!!}--}}
            @inject('permissionPresenter','App\Repositories\Presenter\PermissionPresenter')

            {!! $permissionPresenter->menus() !!}
        </ul>

    </div>
</div>

<!-- 左侧菜单结束 -->

