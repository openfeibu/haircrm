<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ route('home') }}">主页</a><span lay-separator="">/</span>
            <a><cite>参数设置</cite></a>
        </div>
    </div>
    <div class="main_full">
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('setting/updateParameter')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">PayPal费率</label>
                        <div class="layui-input-inline">
                            <input type="text" name="paypal_fee"  autocomplete="off" placeholder="" class="layui-input" value="{{$parameter['paypal_fee']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">月总业绩目标</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total_monthly_performance_target" autocomplete="off" placeholder="" class="layui-input" value="{{$parameter['total_monthly_performance_target']}}">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">年总业绩目标</label>
                        <div class="layui-input-inline">
                            <input type="text" name="total_yearly_performance_target" autocomplete="off" placeholder="" class="layui-input" value="{{$parameter['total_yearly_performance_target']}}">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<script>
    layui.use(['jquery','element','form','table','upload'], function(){
        var form = layui.form;
        var $ = layui.$;
        //监听提交
        form.on('submit(demo1)', function(data){
            data = JSON.stringify(data.field);
            data = JSON.parse(data);
            data['_token'] = "{!! csrf_token() !!}";
            var load = layer.load();
            $.ajax({
                url : "{{guard_url('setting/updateParameter')}}",
                data :  data,
                type : 'POST',
                success : function (data) {
                    layer.close(load);
                    layer.msg('更新成功');
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    layer.msg('服务器出错');
                }
            });
            return false;
        });

    });
</script>