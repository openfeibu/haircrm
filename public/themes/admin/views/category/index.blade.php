
<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('category.index') }}"><cite>{{ trans('category.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="{{ route('category.create') }}">添加分类</a></button>
                </div>

            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>

            <div id="category" class="demo-tree demo-tree-box" style="man-width: 200px;"></div>
        </div>
    </div>
</div>

<script>
    layui.use(['tree', 'util','jquery'], function() {
        var tree = layui.tree
                , layer = layui.layer
                , util = layui.util
                , $ = layui.$;
        var data = {!! $categories !!};
        tree.render({
            elem: '#category'
            ,data: data
            ,edit: ['add', 'update', 'del'] //操作节点的图标
            ,click: function(obj){
                //layer.msg(JSON.stringify(obj.data));
                var data = obj.data;
                window.location.href = "{{ guard_url('category') }}/"+data.id;
                return false;
            }
            ,operate: function(obj){
                var type = obj.type; //得到操作类型：add、edit、del
                var data = obj.data; //得到当前节点的数据
                var elem = obj.elem; //得到当前节点元素

                //Ajax 操作
                var id = data.id; //得到节点索引
                var ajax_data = {};
                ajax_data['_token'] = "{!! csrf_token() !!}";

                if(type === 'add'){ //增加节点
                    //返回 key 值
                    window.location.href = "{{ guard_url('category/create') }}?parent_id="+id;
                    return false;
                } else if(type === 'update'){ //修改节点
                    var load = layer.load();
                    var new_name = elem.find('.layui-tree-txt').html();
                    console.log(new_name);
                    ajax_data['name'] = new_name;
                    $.ajax({
                        url : "{{ guard_url('category') }}"+'/'+data.id,
                        data : ajax_data,
                        type : 'PUT',
                        success : function (data) {
                            layer.close(load);
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            layer.msg('服务器出错');
                        }
                    });

                } else if(type === 'del'){ //删除节点
                    var load = layer.load();

                    $.ajax({
                        url : "{{ guard_url('category') }}"+'/'+data.id,
                        data : ajax_data,
                        type : 'delete',
                        success : function (data) {
                            layer.close(load);
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            layer.msg('服务器出错');
                        }
                    });
                };
            }
        });

        $('select').val('xx');
    });
</script>