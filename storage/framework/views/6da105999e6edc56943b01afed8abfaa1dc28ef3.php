<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a><cite><?php echo e(trans('new_customer.add')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <?php echo Theme::partial('message'); ?>

            <div class="tabel-message">
                <form class="form-horizontal" method="POST" action="<?php echo e(guard_url('new_customer_import/submit')); ?>" enctype="multipart/form-data"  id="new_customer_submit_import_form">
                    <div class="layui-row layui-col-space10">
                        <div class="tabel-btn layui-col-md12">
                            <button class="layui-btn layui-btn-warm "><a href="<?php echo e(url('image/original/system/new_customer_template.xlsx')); ?>">下载模板</a></button>
                        </div>
                         <div class="tabel-btn layui-col-md12">
                            <?php echo e(csrf_field()); ?>

                            <div class="input-file" >
                                选择文件
                                <input id="file" type="file" class="form-control" name="file" required>
                            </div>
                             <label class="fileText">未选中文件</label>
                            <button type="button" class="layui-btn layui-btn-normal new_customer_submit_import_btn">确定</button>
                            <span class="layui-word-aux des_content">（注意：请严格按照模板的格式上传Excel！）</span>
                        </div>
                    </div>
                 

                </form>
            </div>

        </div>
    </div>
</div>



<script>

    layui.use(['jquery','element','table'], function(){
        var table = layui.table;
        var form = layui.form;
        var $ = layui.$;
        $(".new_customer_submit_import_btn").click(function(){
            var fileFlag = false;

            $("input[name='file']").each(function(){
                if($(this).val()!="") {
                    fileFlag = true;
                }
            });
            if(!fileFlag) {
                layer.msg("请选择文件");
                return false;
            }

            layer.msg('上传中', {
                icon: 16
                ,shade: 0.01
                ,time:0
            });
            $("#new_customer_submit_import_form").submit();
        });
		$(".input-file input").on('change', function( e ){
            //e.currentTarget.files 是一个数组，如果支持多个文件，则需要遍历
            var name = e.currentTarget.files[0].name;
            $(".fileText").text(name)
		});

    });
</script>
