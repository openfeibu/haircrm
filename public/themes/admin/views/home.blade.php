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
                            <b>最热销产品</b>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">热</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $today_order_count }}</p>
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>本月最热销产品</b>
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">月</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $order_count }}</p>
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
			<div class="power-box  fb-clearfix">
				<p>常用功能</p>
				<div class="power-box-con">
					<div class="power-box-item layui-col-md2">
						<a href="{{ guard_url('order/create') }}">
							添加订单
						</a>
					</div>	
					<div class="power-box-item layui-col-md2">
						<a href="{{ guard_url('customer/create') }}">
							添加客户
						</a>
					</div>
                    <div class="power-box-item layui-col-md2">
                        <a href="{{ guard_url('new_customer/create') }}">
                            添加收集客户
                        </a>
                    </div>
					<div class="power-box-item layui-col-md2">
						<a href="{{ guard_url('goods/create') }}">
							添加产品
						</a>
					</div>	
					<div class="power-box-item layui-col-md2">
						<a href="{{ guard_url('mail_schedule') }}">
							营销邮箱
						</a>
					</div>	
				</div>	
			</div>
        </div>

        <div class="layui-col-md12">
            <div class="layui-card">
               <div class="layui-card-header">柱形图</div>
                <div class="layui-card-body">
                    <div id="EchartZhu" style="width: 500px;height: 500px;"> </div>
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
        var echarts = layui.echarts;
        console.log(echarts);
        var chartZhu = echarts.init(document.getElementById('EchartZhu'));
        //指定图表配置项和数据
        var optionchart = {
            title: {
                text: '商品订单'
            },
            tooltip: {},
            legend: {
                data: ['销量']
            },
            xAxis: {
                data: ['周一', '周二', '周三', '周四', '周五', '周六', '周天']
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: '销量',
                type: 'bar', //柱状
                data: [100,200,300,400,500,600,700],
                itemStyle: {
                    normal: { //柱子颜色
                        color: 'red'
                    }
                },
            },{
                name:'产量',
                type:'bar',
                data:[120,210,340,430,550,680,720],
                itemStyle:{
                    normal:{
                        color:'blue'
                    }
                }
            }]
        };

        var optionchartZhe = {
            title: {
                text: '商品订单'
            },
            tooltip: {},
            legend: { //顶部显示 与series中的数据类型的name一致
                data: ['销量', '产量', '营业额', '单价']
            },
            xAxis: {
                // type: 'category',
                // boundaryGap: false, //从起点开始
                data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
            },
            yAxis: {
                type: 'value'
            },
            series: [{
                name: '销量',
                type: 'line', //线性
                data: [145, 230, 701, 734, 1090, 1130, 1120],
            }, {
                name: '产量',
                type: 'line', //线性
                data: [720, 832, 801, 834, 1190, 1230, 1220],
            }, {
                smooth: true, //曲线 默认折线
                name: '营业额',
                type: 'line', //线性
                data: [820, 932, 901, 934, 1290, 1330, 1320],
            }, {
                smooth: true, //曲线
                name: '单价',
                type: 'line', //线性
                data: [220, 332, 401, 534, 690, 730, 820],
            }]
        };

        var optionchartBing = {
            title: {
                text: '商品订单',
                subtext: '纯属虚构', //副标题
                x: 'center' //标题居中
            },
            tooltip: {
                // trigger: 'item' //悬浮显示对比
            },
            legend: {
                orient: 'vertical', //类型垂直,默认水平
                left: 'left', //类型区分在左 默认居中
                data: ['单价', '总价', '销量', '产量']
            },
            series: [{
                type: 'pie', //饼状
                radius: '60%', //圆的大小
                center: ['50%', '50%'], //居中
                data: [{
                    value: 335,
                    name: '单价'
                },
                    {
                        value: 310,
                        name: '总价'
                    },
                    {
                        value: 234,
                        name: '销量'
                    },
                    {
                        value: 135,
                        name: '产量'
                    }
                ]
            }]
        };
        chartZhu.setOption(optionchart, true);
    });
</script>