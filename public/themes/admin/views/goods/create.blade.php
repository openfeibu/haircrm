<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('goods.index') }}"><cite>{{ trans('goods.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}{{ trans('goods.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('goods')}}" method="post" lay-filter="fb-form">
                    <input type="hidden" name="attribute_id" autocomplete="off" placeholder="" class="layui-input" value="0" id="attribute_id">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">选择分类 *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="category_id" id="category_tree"lay-verify="tree" autocomplete="off" placeholder="请选择分类(加载中)" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('goods.label.purchase_price') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="goods_purchase_price" autocomplete="off" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('goods.label.selling_price') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="goods_selling_price" autocomplete="off" placeholder="" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2" id="attribute_content" style="display: none;">

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
            placeholder: '请选择分类',
            accordion:true,//手风琴模式
            //多选
            showCheckbox: false,
            //连线
            showLine: true,
            //选中节点(依赖于 showCheckbox 以及 key 参数)。
            //checked: [11, 12],
            //展开节点(依赖于 key 参数)
            spread: [1],
            // 点击回调
            click: function(obj){
                var load = layer.load();
                var ajax_data = {};
                ajax_data['_token'] = "{!! csrf_token() !!}";
                ajax_data['category_id'] = obj.id;
                $("#attribute_content").hide().html("");
                $.ajax({
                    url : "{{ guard_url('category_goods') }}",
                    data : ajax_data,
                    type : 'get',
                    success : function (data) {
                        $("#attribute_id").val(0);
                        if(data.code != 0)
                        {
                            layer.close(load);
                            layer.msg(data.msg);
                            return false;
                        }
                        if(!$.isEmptyObject(data.data))
                        {
                            window.location.href = "{{ guard_url('goods') }}/"+data.data.goods.id;
                            return false;
                        }

                        $.ajax({
                            url : "{{ guard_url('attribute_content') }}",
                            data : ajax_data,
                            type : 'get',
                            success : function (data) {
                                layer.close(load);
                                if(data.code != 0)
                                {
                                    layer.close(load);
                                    layer.msg(data.msg);
                                    return false;
                                }
                                var attribute_id = data.data.attribute_id;
                                var html = data.data.content;
                                if(attribute_id)
                                {
                                    $("#attribute_id").val(attribute_id);
                                    $("#attribute_content").show().html(html);
                                    form.render();
                                }

                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        $.ajax_error(jqXHR, textStatus, errorThrown);
                    }
                });


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