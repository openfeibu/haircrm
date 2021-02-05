<div class="main">
    <div class="main_full fb-clearfix" style="margin-top: 15px;">

        <div class="layui-col-md12">
            <div class="layui-card-box layui-col-space15  fb-clearfix">
                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>最热销产品</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">热</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height:700px">

                            <div id="hotChart" style="width: 100%;height: 700px;">


                            </div>


                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>本月最热销产品</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">月</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height:700px;">
                            <div id="month_hotChart" style="width: 100%;height: 700px;">


                            </div>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header"> <b>交易走势</b></div>
                        <form class="layui-form" action="" lay-filter="fb-form" id="trading_form">
                                <div class="layui-row">
                                    <div class="layui-col-md12 "  style="margin:15px">
                                        <div class="layui-form-item">
                                            <div class="layui-inline">
                                                <label class="layui-form-label">选择时间：</label>
                                                <div class="layui-input-inline" >
                                                    <select name="date_type" class="search_key layui-select date_type" lay-filter="date_type" id="date_type" >
                                                        <option value="days">近7天</option>
                                                        <option value="this_month" selected>本月</option>
                                                        <option value="last_month">上个月</option>
                                                        <option value="this_year">今年</option>
                                                        <option value="last_year">去年</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <label class="layui-form-label" style="width: 100px;">选择业务员：</label>
                                                <div class="layui-input-inline" >
                                                    <select name="salesman_id" class="search_key layui-select salesman_id">
                                                        @inject('salesmanRepository','App\Repositories\Eloquent\SalesmanRepository')
                                                        <option value="">所有</option>
                                                        @foreach($salesmanRepository->orderBy('name','asc')->orderBy('id','desc')->get() as $key => $salesman)
                                                            <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="layui-inline">
                                                <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        <div class="layui-card-body">
                            <div id="trading" style="width: 100%;height: 500px;"> </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </div>
    <div class="copy">© CopyRight 2020, 飞步科技, Inc.All Rights Reserved.</div>
</div>


<script>
    layui.use(['jquery','element','form','table','echarts'], function(){
        var form = layui.form;
        var element = layui.element;
        var $ = layui.$;
        (function (){
            var echarts = layui.echarts;
            var trading = echarts.init(document.getElementById('trading'));
            //指定图表配置项和数据
            var trading_option = {
                title: {
                    text: ''
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {

                    data: ['成交额（$）','成交量（单）'],

                },
                xAxis: {
                    data: []
                },
                yAxis: [
                    {
                        name: '成交额（$）',
                        type: 'value',
                    },{
                        name: '成交量（单）',
                        type: 'value',
                    }
                ],
                series: [{

                    name:'成交额（$）',
                    type:'line',
                    data:[],
                    label: {
                        show:true
                    },
                    itemStyle:{
                        normal:{
                            color:'#4fa4c7'
                        }
                    },
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 5,
                    sampling: 'average',
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: 'rgba(79,164,199,0.8)'
                        }, {
                            offset: 1,
                            color: 'rgba(79,164,199,0.3)'
                        }])
                    },
                },{
                    name: '成交量（单）',
                    yAxisIndex: 1,
                    type: 'line', //柱状
                    data: [],
                    label: {
                        show:true
                    },
                    itemStyle: {
                        normal: { //柱子颜色
                            color: '#f5a624'
                        }
                    },
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 5,
                    sampling: 'average',
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: 'rgba(245,166,36,0.8)'
                        }, {
                            offset: 1,
                            color: 'rgba(245,166,36,0.3)'
                        }])
                    },
                }]
            };
            trading.setOption(trading_option, true);
            trading.showLoading();

            ajax_trading();

            $('#trading_form .layui-btn').on('click', function(){
                trading.showLoading();
                ajax_trading();
            });

            function ajax_trading() {
                var date_type = $('#trading_form').find('.date_type').val();
                var salesman_id =  $('#trading_form').find('.salesman_id').val();

                $.get('{{ guard_url("statistic/trading") }}?date_type='+date_type+'&salesman_id='+salesman_id).done(function (data) {
                    trading.hideLoading();
                    // 填入数据
                    trading.setOption({
                        xAxis: {
                            data: data.data.date_arr
                        },
                        series: [{
                            // 根据名字对应到相应的系列
                            name: '成交额（$）',
                            data: data.data.turnover_arr,

                            itemStyle:{
                                normal:{
                                    color:'#4fa4c7'
                                }
                            }
                        },{
                            // 根据名字对应到相应的系列
                            name: '成交量（单）',
                            data: data.data.order_count_arr,
                            type: 'line', //柱状
                            yAxisIndex: 1,
                            itemStyle: {
                                normal: { //柱子颜色
                                    color: '#f5a624'
                                }
                            },
                        }
                        ]
                    });
                })
            }

        })();

        //最热销图表
        (function (){
            var echarts = layui.echarts;
            var hotChart = echarts.init(document.getElementById('hotChart'));
            var hot_goods_list = {!! json_encode($hot_goods_list) !!}
            var xAxis = [];
            var procount = [];
            var pronum = [];
            for(var i = hot_goods_list.length-1;i>0;i--){

                xAxis.push(hot_goods_list[i].goods_name);
                procount.push(hot_goods_list[i].count);
                pronum.push(hot_goods_list[i].sum)
            }
            console.log(xAxis)
            //指定图表配置项和数据
            var option = {
                title: {

                },
                tooltip: {
                    trigger: 'axis'
                },
                grid: {
                    left: '10px',
                    right: '50px',

                    containLabel: true
                }  ,
                legend: {
                    data: ['下单次数（次）', '总数量（个）'],

                },
                yAxis: [
                    {
                        type: 'category',
                        data: xAxis,



                    }
                ],
                xAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '下单次数（次）',
                        type: 'bar',
                        data: procount,
                        markPoint: {
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        color: '#333',//气泡中字体颜色
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
                                    0, 0, 1, 0,
                                    [
                                        {offset: 0,color: 'rgba(255,206,69,0.5)'},

                                        {offset: 1, color: 'rgba(255,206,69,1)'}
                                    ]
                            )

                        },

                    },
                    {
                        name: '总数量（个）',
                        type: 'bar',
                        data: pronum,
                        markPoint: {
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        color: '#333',//气泡中字体颜色
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
                                    0, 0, 1, 0,
                                    [
                                        {offset: 0,color: 'rgba(79,164,199,0.5)'},

                                        {offset: 1, color: 'rgba(79,164,199,1)'}
                                    ]
                            )

                        },
                    }
                ]
            };
            hotChart.setOption(option, true);




        })();
        //本月热销图表
        (function (){
            var echarts = layui.echarts;
            var hotChart = echarts.init(document.getElementById('month_hotChart'));
            var this_month_hot_goods_list = {!! json_encode($this_month_hot_goods_list) !!}
            var xAxis = [];
            var procount = [];
            var pronum = [];
            for(var i = this_month_hot_goods_list.length-1;i>0;i--){

                xAxis.push(this_month_hot_goods_list[i].goods_name);
                procount.push(this_month_hot_goods_list[i].count);
                pronum.push(this_month_hot_goods_list[i].sum)
            }
            console.log(xAxis)
            //指定图表配置项和数据
            var option = {
                title: {

                },
                tooltip: {
                    trigger: 'axis'
                },
                grid: {
                    left: '10px',
                    right: '50px',

                    containLabel: true
                }  ,
                legend: {
                    data: ['下单次数（次）', '总数量（个）'],

                },
                yAxis: [
                    {
                        type: 'category',
                        data: xAxis,



                    }
                ],
                xAxis: [
                    {
                        type: 'value'
                    }
                ],
                series: [
                    {
                        name: '下单次数（次）',
                        type: 'bar',
                        data: procount,
                        markPoint: {
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        color: '#333',//气泡中字体颜色
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
                                    0, 0, 1, 0,
                                    [
                                        {offset: 0,color: 'rgba(255,132,0,0.5)'},

                                        {offset: 1, color: 'rgba(255,132,0,1)'}
                                    ]
                            )

                        },
                    },
                    {
                        name: '总数量（个）',
                        type: 'bar',
                        data: pronum,
                        markPoint: {
                            itemStyle:{
                                normal:{
                                    label:{
                                        show: true,
                                        color: '#333',//气泡中字体颜色
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
                                    0, 0, 1, 0,
                                    [
                                        {offset: 0,color: 'rgba(0,150,255,0.5)'},

                                        {offset: 1, color: 'rgba(0,150,255,1)'}
                                    ]
                            )

                        },
                    }
                ]
            };
            hotChart.setOption(option, true);




        })();

    });
</script>