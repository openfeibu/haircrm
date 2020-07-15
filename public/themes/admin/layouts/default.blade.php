<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta content="telephone=no" name="format-detection" />
    <meta name="screen-orientation" content="portrait">
    <meta name="x5-orientation" content="portrait">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{trans('app.site_name')}}</title>
    {!! Theme::asset()->styles() !!}
    {{--<script src='{{ asset('js/jquery-1.7.2.min.js') }}'></script>--}}
    {!! Theme::asset()->scripts() !!}
    {!! Theme::asset()->container('footer')->scripts() !!}
</head>
<script>
    layui.use(['jquery'], function() {
        var $ = layui.$;
        $.ajax_error = function (jqXHR, textStatus, errorThrown) {
            if($.parseJSON(jqXHR.responseText).code == 401){
                layer.msg(jqXHR.responseText.message);
                window.location.href = "{{ guard_url('login') }}"
            }else{
                layer.msg('{{ trans('messages.server_error') }}');
            }
        }
    })
</script>
<body>
    {!! Theme::partial('header') !!}
    {!! Theme::partial('aside') !!}
    {!! Theme::content() !!}
    {!! Theme::partial('footer') !!}
</body>
</html>
