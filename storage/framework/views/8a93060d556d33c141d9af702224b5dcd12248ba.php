<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="<?php echo e(guard_url('home')); ?>"><?php echo e(trans('app.home')); ?></a><span lay-separator="">/</span>
            <a href="<?php echo e(guard_url('goods')); ?>"><cite><?php echo e(trans('goods.title')); ?></cite></a>
        </div>
    </div>
    <div class="main_full">
        <?php echo Theme::partial('message'); ?>

        <div class="layui-col-md12">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm "><a href="<?php echo e(guard_url('goods/create')); ?>"><?php echo e(trans('app.add')); ?> <?php echo e(trans('goods.name')); ?></a></button>
                    <button class="layui-btn layui-btn-danger " data-type="del" data-events="del"><?php echo e(trans('app.delete')); ?></button>
                </div>
                <div class="layui-inline">
                    <input class="layui-input search_key" name="search_name" id="demoReload" placeholder="<?php echo e(trans('goods.label.name')); ?>" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload"><?php echo e(trans('app.search')); ?></button>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" href="<?php echo e(guard_url('goods')); ?>/{{ d.goods_id }}"><?php echo e(trans('app.edit')); ?></a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del"><?php echo e(trans('app.delete')); ?></a>
</script>

<script>
    var main_url = "<?php echo e(guard_url('goods_attribute_value')); ?>";
    var delete_all_url = "<?php echo e(guard_url('goods_attribute_value/destroyAll')); ?>";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '<?php echo e(guard_url('goods')); ?>'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'goods_name',title:'<?php echo e(trans('goods.name')); ?>'}
                ,{field:'purchase_price',title:'<?php echo e(trans('goods.label.purchase_price')); ?>',width:120,edit:'text'}
                ,{field:'selling_price',title:'<?php echo e(trans('goods.label.selling_price')); ?>',width:120,edit:'text'}
                ,{field:'score',title:'<?php echo e(trans('app.actions')); ?>', width:180, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '<?php echo e(config('app.limit')); ?>'
            ,height: 'full-200'
            ,cellMinWidth :'180'
            ,done:function () {
                element.init();
            }
        });
    });
</script>

<?php echo Theme::partial('common_handle_js'); ?>

