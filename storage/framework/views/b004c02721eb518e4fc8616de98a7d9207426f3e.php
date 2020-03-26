<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta content="telephone=no" name="format-detection" />
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-orientation" content="portrait">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(trans('app.site_name')); ?></title>
    <?php echo Theme::asset()->styles(); ?>

    
    <?php echo Theme::asset()->scripts(); ?>

    <?php echo Theme::asset()->container('footer')->scripts(); ?>

</head>
<script>
    layui.use(['jquery'], function() {
        var $ = layui.$;
        $.ajax_error = function (jqXHR, textStatus, errorThrown) {
            if($.parseJSON(jqXHR.responseText).code == 401){
                layer.msg(jqXHR.responseText.message);
                window.location.href = "<?php echo e(guard_url('login')); ?>"
            }else{
                layer.msg('<?php echo e(trans('messages.server_error')); ?>');
            }
        }
    })
</script>
<body>
    <?php echo Theme::partial('header'); ?>

    <?php echo Theme::partial('aside'); ?>

    <?php echo Theme::content(); ?>

    <?php echo Theme::partial('footer'); ?>

</body>
</html>
