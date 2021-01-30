<div class="main">
    <div class="main_full" style="margin-top: 15px;">
        <div class="layui-col-md12">
            <div class="layui-card-box layui-col-space15  fb-clearfix">
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>今日销售额</b>
                            <label>(昨日${{ $yesterday_selling_price }})</label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">${{ $today_selling_price }}<span class="c2">({{ rate_of_increase($today_selling_price,$yesterday_selling_price) }})</span></p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>总销售额</b>
                            {{--<label>(昨日$222)</label>--}}
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">${{ $selling_price }}</p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>今日订单量</b>
                            <label>(昨日{{ $yesterday_order_count }})</label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $today_order_count }}<span class="c2">({{ rate_of_increase($today_order_count,$yesterday_order_count) }})</span></p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>总订单量</b>
                            {{--<label>(昨日$222)</label>--}}
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
                            <b>今日成交订单量</b>
                            <label>(昨日{{ $yesterday_paid_order_count }})</label>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">日</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $today_paid_order_count }}<span class="c2">({{ rate_of_increase($today_paid_order_count,$yesterday_paid_order_count) }})</span></p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>总成交订单量</b>
                            {{--<label>(昨日$222)</label>--}}
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $order_paid_count }}</p>

                        </div>
                    </div>
                </div>

                 <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>客户数</b>
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
                            <b>收集客户数</b>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $new_customer_count }}</p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>待发货</b>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $unshipped_count }}</p>

                        </div>
                    </div>
                </div>
               
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>产品数</b>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $goods_count }}</p>

                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>已发营销邮件</b>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $mail_sent_count }}</p>

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

            </div>
        </div>
      
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">交易走势</div>
                <div class="">
                    <form class="layui-form" action="" lay-filter="fb-form">
                        <div class="layui-row">
						<div class="layui-col-md12 "  style="margin:15px">
						 <div class="layui-inline">选择时间：</div>
                            <div class="layui-inline">
								
                                <select name="date_type" class="layui-select" lay-filter="date_type" id="date_type">
                                    <option value="days">近7天</option>
                                    <option value="this_month" selected>本月</option>
                                    <option value="last_month">上个月</option>
                                    <option value="this_year">今年</option>
                                    <option value="last_year">去年</option>
                               </select>
                           </div>
						   </div>
                       </div>
                   </form>
               </div>
               <div class="layui-card-body">
                   <div id="trading" style="width: 100%;height: 500px;"> </div>
               </div>
           </div>
       </div>
   </div>
</div>
<div class="copy">© CopyRight 2020, 飞步科技, Inc.All Rights Reserved.</div>

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
		   var date_type = $('#date_type').val();
		   ajax_trading(date_type);


		   form.on('select(date_type)', function (data) {
			   var date_type = $('#date_type').val();

			   trading.showLoading();
			   ajax_trading(date_type);
		   });
		   function ajax_trading(date_type) {
			   $.get('{{ guard_url("trading") }}?date_type='+date_type).done(function (data) {
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