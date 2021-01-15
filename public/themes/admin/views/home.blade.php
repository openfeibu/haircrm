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
                <div class="layui-col-sm6 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>最热销产品</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">热</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height:200px;">
                            @foreach($hot_goods_list as $key => $goods_list)
                                <p class="">
                                    <span class="layui-badge layui-bg-red" style="float: none">{!! $key+1 !!}</span>
                                    {!! $goods_list['goods_name'] !!}
                                    <span class="layuiadmin-big-font">{{ $goods_list['count'] }}</span>次
                                    <span class="layuiadmin-big-font">{{ $goods_list['sum'] }}</span>个</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="layui-col-sm6 layui-col-md6">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>本月最热销产品</b>
                            <span class="layui-badge layui-bg-red layuiadmin-badge">月</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" style="height:200px;">
                            @foreach($this_month_hot_goods_list as $key => $goods_list)
                                <p class="">
                                    <span class="layui-badge layui-bg-red" style="float: none">{!! $key+1 !!}</span>
                                    {!! $goods_list['goods_name'] !!}
                                    <span class="layuiadmin-big-font">{{ $goods_list['count'] }}</span>次
                                    <span class="layuiadmin-big-font">{{ $goods_list['sum'] }}</span>个</p>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="layui-col-md12">
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
                <div class="layui-card-header">交易走势</div>
                <div class="">
                    <form class="layui-form" action="" lay-filter="fb-form">
                        <div class="layui-row mb10">
                            <div class="layui-inline">
                                <select name="date_type" class="layui-select" lay-filter="date_type" id="date_type">
                                    <option value="days">近7天</option>
                                    <option value="this_month">本月</option>
                                    <option value="last_month">上个月</option>
                                    <option value="this_year">今年</option>
                                    <option value="last_year">去年</option>
                               </select>
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
       var echarts = layui.echarts;
       console.log(echarts);
       var trading = echarts.init(document.getElementById('trading'));
       //指定图表配置项和数据
       var trading_option = {
           title: {
               text: '交易走势'
           },
           tooltip: {
               trigger: 'axis'
           },
           legend: {
               data: ['成交量','成交额']
           },
           xAxis: {
               data: []
           },
           yAxis: {
               type: 'value'
           },
           series: [{
               name: '成交量',
               type: 'line', //柱状
               data: [],
               itemStyle: {
                   normal: { //柱子颜色
                       color: 'red'
                   }
               },
           },{
               name:'成交额',
               type:'line',
               data:[],
               itemStyle:{
                   normal:{
                       color:'blue'
                   }
               }
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
                       name: '成交量',
                       data: data.data.order_count_arr
                   },{
                       // 根据名字对应到相应的系列
                       name: '成交额',
                       data: data.data.turnover_arr
                   },
                   ]
               });
           });
       }
   });
</script>