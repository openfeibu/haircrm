<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('price_calculation_of_plastic_bag') }}"><cite>塑料袋价格计算</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('price_calculation_of_plastic_bag/get_price')}}" method="post" lay-filter="fb-form" id="calculation_price_from">
                    <div class="layui-form-item fb-form-item2">
                        <div class="layui-inline">
                            <label class="layui-form-label">计量单位</label>
                            <div class="layui-input-inline" style="width: 100px;">
                                <select name="measuring_unit" lay-filter="measuring_unit" lay-verify="required">
                                    <option value="cm">厘米 cm</option>
                                    <option value="inch">英寸 inch</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">宽度</label>
                            <div class="layui-input-inline" style="width: 100px;">
                                <input type="text" name="width" autocomplete="off" class="layui-input" lay-verify="number">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">长度</label>
                            <div class="layui-input-inline" style="width: 100px;">
                                <input type="text" name="length" autocomplete="off" class="layui-input" lay-verify="number">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">厚度 丝 </label>
                        <div class="layui-input-inline" style="width: 100px;">
                            <input type="text" name="thickness" autocomplete="off" class="layui-input" lay-verify="number">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">出厂价 元/kg</label>
                        <div class="layui-input-block" >
                            <input type="text" name="factory_price"  autocomplete="off" class="layui-input" lay-verify="number" >
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">比重</label>
                        <div class="layui-input-block" >
                            <input type="text" name="proportion"  autocomplete="off" class="proportion layui-input" lay-verify="number" value="1.85">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">计算结果 kg/个</label>
                        <div class="layui-input-block" >
                            <input type="text" name="calculation_kg" autocomplete="off" class="calculation_kg layui-input" disabled>
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">计算结果 元/个</label>
                        <div class="layui-input-block" >
                            <input type="text" name="calculation_price_rmb" autocomplete="off" class="calculation_price_rmb layui-input" disabled>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" id="submit-btn" lay-submit="" lay-filter="cm">计算</button>
                        </div>
                    </div>


                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>

<script>

    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        form.on('select(measuring_unit)', function(data){
            console.log(data.value);
            if(data.value == 'cm')
            {
                $(".proportion").val('1.85');
            }
            if(data.value == 'inch')
            {
                $(".proportion").val('2.63');
            }
            form.render();
        });

        form.on('submit(cm)', function(data){

            console.log(data.elem) //被执行事件的元素DOM对象，一般为button对象
            console.log(data.form) //被执行提交的form对象，一般在存在form标签时才会返回
            console.log(data.field) //当前容器的全部表单字段，名值对形式：{name: value}
            var ajax_data = data.field;
            ajax_data.token = "{!! csrf_token() !!}";
            var load = layer.load();
            $.ajax({
                url : "{{guard_url('price_calculation_of_plastic_bag/get_price')}}",
                data : ajax_data,
                type : 'post',
                success : function (data) {
                    layer.close(load);
                    $('#calculation_price_from').find('.calculation_price_rmb').val(data.data.calculation_price_rmb);
                    $('#calculation_price_from').find('.calculation_kg').val(data.data.calculation_kg);
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });

            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        });

    });
</script>
