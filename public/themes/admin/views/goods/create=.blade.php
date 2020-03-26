<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('goods.index') }}"><cite>{{ trans('goods.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}{{ trans('goods.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('goods')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item">
                        {!! Theme::widget('category')->render() !!}
                    </div>

                    {{--<div class="layui-form-item fb-form-item2">--}}
                        {{--<label class="layui-form-label">选择分类</label>--}}
                        {{--<div class="layui-input-inline">--}}
                            {{--<input type="text" name="category_id" id="category_tree"lay-verify="tree" autocomplete="off" placeholder="请选择分类(加载中)" class="layui-input">--}}
                        {{--</div>--}}
                    {{--</div>--}}

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">选择尺寸</label>
                        <div class="fb-form-item-box fb-clearfix">
                            @foreach($attribute_values as $key => $attribute_value)
                            <div class="layui-input-block">
                                <input type="checkbox" name="attribute_value[{{ $attribute_value['id'] }}]" lay-skin="primary" title="{{ $attribute_value['value'] }}" checked="">
                                <input type="text" name="purchase_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.purchase_price') }}" class="layui-input minInput">
                                <input type="text" name="selling_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.selling_price') }}" class="layui-input minInput">
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {!!Form::token()!!}
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

<script>
    layui.use(['treeSelect', 'form', 'layer'], function () {
        var treeSelect= layui.treeSelect,
                form = layui.form,
                $ = layui.jquery,
                layer = layui.layer;

        treeSelect.render({
            elem: '#category_tree',
            data: '/categories_tree',
            headers: {},
            type: 'get',
            // 占位符
            placeholder: '请选择',
            //多选
            showCheckbox: false,
            //连线
            showLine: true,
            //选中节点(依赖于 showCheckbox 以及 key 参数)。
            //checked: [11, 12],
            //展开节点(依赖于 key 参数)
            spread: [1],
            // 点击回调
            click: function(d){
                console.log(d);
            },
            // 加载完成后的回调函数
            success: function (d) {
                console.log(d);
            }
        });
    });
</script>

<script>
</script>