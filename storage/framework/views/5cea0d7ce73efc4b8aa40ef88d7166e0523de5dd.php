<label class="layui-form-label">选择分类</label>
<div class="fb-form-item-box fb-clearfix">
    <div class="layui-input-block">
        <select name="category_id[]" lay-filter="checkBox" lay-verify="required">
            <option value="">请选择类型</option>
            <?php $__currentLoopData = $top_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>
<script>

    layui.use(['element','form',"jquery"], function(){
        var form = layui.form;
        var upload = layui.upload;
        var $ = layui.$

        form.on('select(checkBox)', function(obj){
            var load = layer.load();
            var ajax_data = {};
            ajax_data['_token'] = "<?php echo csrf_token(); ?>";
            ajax_data['id'] = obj.value;
            $(obj.othis).parents(".layui-input-block").nextAll(".layui-input-block").remove();
            $.ajax({
                url : "/categories",
                data : ajax_data,
                type : 'get',
                success : function (data) {
                    layer.close(load);
                    if(data.length > 0)
                    {
                        var html = ` <div class="layui-input-block">
                                <select name="category_id[]" lay-filter="checkBox" lay-verify="required">
                                  <option value=""></option>`;
                        $.each(data,function(key,val){
                            html += "<option value='"+ val.id +"'>"+val.name+"</option>";
                                $('#' + key).addClass('focus');
                        });
                        html += "</select></div>";
                        $(obj.othis).parents(".layui-input-block").after(html);
                    }
                    form.render('select');
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    layer.msg('服务器出错');
                }
            });

        });
    });

</script>