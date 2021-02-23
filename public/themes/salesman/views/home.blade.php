<div class="main">
    <div class="main_full fb-clearfix" style="margin-top: 15px;">
		<div class="layui-col-md12">
            <div class="layui-card-box layui-col-space15 fb-clearfix">
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            今日成交订单数
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $today_order_count }}</p>

                        </div>
                    </div>
                </div>

                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            总成交订单数
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $order_count }}</p>

                        </div>
                    </div>
                </div>

                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            客户数
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $customer_count }}</p>

                        </div>
                    </div>
                </div>

                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            收集客户数
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $new_customer_count }}</p>

                        </div>
                    </div>
                </div>


            </div>

        </div>

		<div class="layui-col-md12">
            <div class="layui-card-box layui-col-space15  fb-clearfix">
				<div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>业绩概览</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">业绩</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height:220px">
							<div class="layui-col-sm5 layui-col-md5">
								<div class="layui-col-sm6 layui-col-md6">
									<div id="Monthly-performance" style="width: 100%;height: 220px;"> 
								
								
									</div>
								</div>
								<div class="layui-col-sm6 layui-col-md6 performance-right">
									<div class="t">本月业绩目标</div>
									<div class="num"><span>${{ $total_month_performance }}</span>/${{ $monthly_performance_target }}</div>
									
								</div>
							</div>
							<div class="layui-col-sm5 layui-col-md5">
								<div class="layui-col-sm6 layui-col-md6">
									<div id="year-performance" style="width: 100%;height: 220px;"> 
								
								
									</div>
								</div>
								<div class="layui-col-sm6 layui-col-md6 performance-right">
									<div class="t">本年业绩目标</div>
									<div class="num"><span>${{ $total_year_performance }}</span>/${{ $yearly_performance_target }}</div>
									
								</div>
							</div>
							<div class="layui-col-sm2 layui-col-md2">
								
								<div class="layui-col-sm6 layui-col-md6 performance-right" style="text-align:center">
									<div class="t">总销售额</div>
									<div class="num"><span>${{ $paid_total }}</span></div>
									
								</div>
							</div>
                           
                        </div>
                    </div>
                </div>
				<div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>本月录入客户走势</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">录客</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height:600px">
						
							<div id="customerChart" style="width: 100%;height: 600px;">
							
							
							</div>
						
                           
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
   	   //本月业绩
       (function (){
		   var echarts = layui.echarts;
		   var Monthly_performance = echarts.init(document.getElementById('Monthly-performance'));
		   var this_month_hot_goods_list = '';
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
		   option = {
			 
                tooltip: {
                    trigger: 'item'
                },
                graphic: [  //为环形图中间添加文字
                    {
                        type: "text",
                        left: "center",
                        top: "center",
                        style: {
                            text: "{{ $total_month_performance_percent }}",
                            textAlign: "center",
                            fill: "#32373C",
                            fontSize: 28,
                        },
                    },
                ],
                series: [
                    {
                        name: '月业绩目标',
                        type: 'pie',
                        radius: ['50%', '70%'],
                        avoidLabelOverlap: false,
                        itemStyle: {
                            borderRadius: 10,
                            borderColor: '#fff',
                            borderWidth: 2
                        },
                        label: {
                            show: false,
                            position: 'center'
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            {value: "{!! $total_month_performance !!}", name: '已完成业绩'},
                            {value: "{!! $total_month_performance > $monthly_performance_target ? 0 : $monthly_performance_target-$total_month_performance !!}", name: '未完成业绩'},

                        ],
                        color: [
                            new echarts.graphic.LinearGradient(1, 1, 0, 0, [{
                                offset: 0,
                                color: 'rgba(250,85,89,0.5)'
                            },
                            {
                                offset: 0.9,
                                color: 'rgba(250,85,89,1)'
                            }]),
                            new echarts.graphic.LinearGradient(1, 1, 0, 0, [{
                                offset: 0,
                                color: 'rgba(195,195,195,0.5)'
                            },
                            {
                                offset: 0.9,
                                color: 'rgba(195,195,195,1)'
                            }])
                         ]
                    }
                ]
		    };
		    Monthly_performance.setOption(option, true);

       })();
	   
       //本年业绩
       (function (){
		   var echarts = layui.echarts;
		   var year_performance = echarts.init(document.getElementById('year-performance'));
		   var this_month_hot_goods_list = '';
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
		   option = {
               tooltip: {
                    trigger: 'item'
                },
               graphic: [  //为环形图中间添加文字
                            {
                            type: "text",
                            left: "center",
                            top: "center",
                            style: {
                                text: "{{ $total_year_performance_percent }}",
                                textAlign: "center",
                                fill: "#32373C",
                                fontSize: 28,
                            },
                            },
                        ],
               series: [
                   {
                        name: '年业绩目标',
                        type: 'pie',
                        radius: ['50%', '70%'],
                        avoidLabelOverlap: false,
                        itemStyle: {
                            borderRadius: 10,
                            borderColor: '#fff',
                            borderWidth: 2
                        },
                        label: {
                            show: false,
                            position: 'center'
                        },
                        labelLine: {
                            show: false
                        },
                        data: [
                            {value: "{!! $total_year_performance !!}", name: '已完成业绩'},
                            {value: "{!! $total_year_performance > $yearly_performance_target ? 0 : $yearly_performance_target-$total_year_performance !!}", name: '未完成业绩'},

                        ],
                        color: [
                                new echarts.graphic.LinearGradient(1, 1, 0, 0, [{
                                    offset: 0,
                                    color: 'rgba(250,85,89,0.5)'
                                },
                                {
                                    offset: 0.9,
                                    color: 'rgba(250,85,89,1)'
                                }]),
                                new echarts.graphic.LinearGradient(1, 1, 0, 0, [{
                                    offset: 0,
                                    color: 'rgba(195,195,195,0.5)'
                                },
                                {
                                    offset: 0.9,
                                    color: 'rgba(195,195,195,1)'
                                }])
                        ]
                    }
               ]
           };
           year_performance.setOption(option, true);

	   })();
       //客户录入数
       (function (){
		   var echarts = layui.echarts;
		   var customerChart = echarts.init(document.getElementById('customerChart'));
		   var xAxis = [];
		   var procount = [];

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
           customerChart.setOption(option, true);
           customerChart.showLoading();
           getMonthNewCustomers();
           function getMonthNewCustomers() {
               $.get('{{ guard_url("getMonthNewCustomers") }}').done(function (data) {
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

	   })();
   })
</script>