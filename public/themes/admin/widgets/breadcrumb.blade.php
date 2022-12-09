<div class="layui-card fb-minNav">
    <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
        <a href="{{ route('home') }}">主页</a><span lay-separator="">/</span>
        @foreach($breadcrumbs as $key => $breadcrumb)
            @if($key+1 == $count)
                <a><cite>{{ $breadcrumb->name }}</cite></a>
            @else
                <a @if($breadcrumb->slug && Route::has($breadcrumb->slug))href="{{ route($breadcrumb->slug) }}"@endif><cite>{{ $breadcrumb->name }}</cite></a><span lay-separator="">/</span>
            @endif
        @endforeach
    </div>
</div>