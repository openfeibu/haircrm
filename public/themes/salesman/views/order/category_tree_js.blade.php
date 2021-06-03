<script>
    var sizes = {};
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
                sizes = {};
                $("#goods_attributes").hide();
                var load = layer.load();
                var ajax_data = {};
                ajax_data['_token'] = "{!! csrf_token() !!}";
                ajax_data['category_id'] = obj.id;
                $.ajax({
                    url : "{{ guard_url('category_goods') }}",
                    data : ajax_data,
                    type : 'get',
                    success : function (data) {
                        layer.close(load);
                        if(data.code != 0)
                        {
                            layer.msg(data.msg);
                        }
                        if(!$.isEmptyObject(data.data.goods))
                        {
                            var goods = data.data.goods;


                            $('#size').html("");
                            var html = '';
                            $.each(data.data.goods_list,function (i,val) {
                                //val['goods_name'] = val['goods_name'];
                                sizes[i] = val;
                                html += "<a href='javascript:;' class='layui-btn layui-btn-warm' i="+i+">"+val.attribute_value+"</a>";
                            })
                            if(goods.attribute_id) {
                                $("#goods_attributes").show();
                                $('#size').html(html);
                            } else{
                                $.onNodeClick(sizes[0]);
                            }
                        }else{
                            layer.msg("该分类下未发现产品，请先添加产品");
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        layer.msg('服务器出错');
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
