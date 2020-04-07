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
               
               
                <div class="layui-col-sm6 layui-col-md3">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            产品数
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
                            已发营销邮件
                            <span class="layui-badge layui-bg-blue layuiadmin-badge">总</span>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list">
                            <p class="layuiadmin-big-font">{{ $selling_price }}</p>

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
						<a href="#">
							营销邮箱
						</a>
					</div>	
				</div>	
			</div>
        </div>
    </div>
</div>
<div class="copy">© CopyRight 2020, 飞步科技, Inc.All Rights Reserved.</div>