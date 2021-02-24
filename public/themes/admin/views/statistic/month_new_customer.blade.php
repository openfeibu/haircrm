<div class="main">
    <div class="main_full fb-clearfix" style="margin-top: 15px;">

        <div class="layui-col-md12">
            <div class="layui-card-box layui-col-space15  fb-clearfix">

                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>月录入客户走势</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">录客</span>
                        </div>
                        <form class="layui-form" action="" lay-filter="fb-form" id="new_customer_form">
                            <div class="layui-row">
                                <div class="layui-col-md12 "  style="margin:15px">
                                    <div class="layui-form-item">
                                        <div class="layui-inline">
                                            <label class="layui-form-label">选择月份:</label>
                                            <div class="layui-input-inline" >
                                                <input class="layui-input search_key" name="year_month" id="year_month" autocomplete="off" style="width: 200px;">
                                            </div>
                                        </div>
                                        <div class="layui-inline">
                                            <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="layui-card-body layuiadmin-card-list" style="height:600px">
                            @foreach($salesmen as $key => $salesman)
                            <div id="customerChart_{{ $salesman['id'] }}" style="width: 100%;height: 600px;">

                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <div class="copy">© CopyRight 2020, 飞步科技, Inc.All Rights Reserved.</div>
</div>


<script>
    layui.use(['jquery','element','form','table','laydate','echarts'], function(){
        var form = layui.form;
        var element = layui.element;
        var $ = layui.$;
        var laydate = layui.laydate;

        laydate.render({
            elem: '#year_month'
            ,type: 'month'
            ,value: '{!! date('Y-m') !!}'
        });


    //客户录入数
        (function (){
            var echarts = layui.echarts;
            @foreach($salesmen as $key => $salesman)
            var customerChart_{{ $salesman['id'] }} = echarts.init(document.getElementById('customerChart_{{ $salesman['id'] }}'));

            //指定图表配置项和数据
            var option_{{ $salesman['id'] }} = {
                title: {
                    text: '{{ $salesman['name'] }}',
                },
                tooltip: {
                    trigger: 'axis'
                },
                grid: {
                    left: '10px',
                    right: '50px',
                    containLabel: true
                } ,
                yAxis: [
                    {
                        type: 'value',
                        minInterval: 30
                    }],
                xAxis: [
                    {
                        type: 'category',
                        data:[],
                        axisLabel: {
                            interval:0,
                            rotate:40
                        }
                    }],
                series: [{
                    name: '客户录入数',
                    type: 'bar',
                    data: [],
                    label: {
                        show:true
                    },
                    markPoint: {
                        itemStyle:{
                            normal:{
                                label:{
                                    show: true,
                                    color: '#fff',//气泡中字体颜色
                                }
                            }
                        },
                        data: [
                            {type: 'max', name: '最大值'},
                            {type: 'min', name: '最小值'}
                        ]
                    },
                    markLine: {
                        data: [
                            {type: 'average', name: '平均值'}
                        ]
                    },
                    itemStyle: {
                        color: new echarts.graphic.LinearGradient(
                                0, 0, 0, 1,
                                [
                                    {offset: 0,color: 'rgba(0,150,255,0.5)'},

                                    {offset: 1, color: 'rgba(0,150,255,0.8)'}
                                ]
                        )
                    },
                }]
            };
            customerChart_{{ $salesman['id'] }}.setOption(option_{{ $salesman['id'] }}, true);
            customerChart_{{ $salesman['id'] }}.showLoading();
            var year_month = $('#year_month').val();
            getMonthNewCustomers("{{ $salesman['id'] }}",year_month);
            @endforeach

            function getMonthNewCustomers(salesman_id,year_month) {
                var customerChart = eval('customerChart_' + salesman_id);
                customerChart.showLoading();
                $.get('{{ guard_url("statistic/get_month_new_customers") }}?salesman_id='+salesman_id+"&year_month="+year_month).done(function (data) {
                    customerChart.hideLoading();
                    // 填入数据
                    customerChart.setOption({
                        xAxis: {
                            data: data.data.date_arr
                        },
                        series: [{
                            // 根据名字对应到相应的系列
                            name: '客户录入数',
                            data: data.data.new_customer_arr,
                        }]
                    });
                })

            }

            $('#new_customer_form .layui-btn').on('click', function(){
                ajax_getMonthNewCustomers();
            });

            function ajax_getMonthNewCustomers() {
                var year_month = $('#year_month').val();
                @foreach($salesmen as $key => $salesman)
                        getMonthNewCustomers("{{ $salesman['id'] }}",year_month);
                @endforeach

            }
        })();
    });
</script>