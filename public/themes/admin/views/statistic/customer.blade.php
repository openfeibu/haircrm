<div class="main">
    <div class="main_full fb-clearfix " style="margin-top: 15px;">

        <div class="layui-row">
            <div class="layui-card-box layui-col-space15  fb-clearfix">
                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <form class="layui-form" action="" lay-filter="fb-form" id="customer_form">
                        <div class="layui-row">
                            <div class="layui-col-md12 "  style="margin:15px">
                                <div class="layui-form-item">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">选择业务员:</label>
                                        <div class="layui-input-inline" >
                                            @inject('salesmanRepository','App\Repositories\Eloquent\SalesmanRepository')
                                            <select name="salesman_id" id="salesman_id" lay-filter="" lay-search>
                                                <option value="">所有</option>
                                                @foreach($salesmanRepository->getActiveSalesmen() as $key => $salesman)
                                                    <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <button class="layui-btn submit" data-type="reload" type="button">{{ trans('app.search') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    </div>
                </div>
                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>本周客户概览</b>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" >

                            <div class="layui-row customer-overview-component-container" >
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本周报价客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_week_quotation_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上周报价客户：<span id="last_week_quotation_customer_count">0</span></p>
                                    </div>
                                </div>
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本周新增客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_week_add_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上周新增客户：<span id="last_week_add_customer_count">0</span></p>
                                    </div>
                                </div>
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本周新成交客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_week_purchase_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上周新成交客户：<span id="last_week_purchase_customer_count">0</span></p>
                                    </div>
                                </div>
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本周复购客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_week_repurchase_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上周复购客户：<span id="last_week_repurchase_customer_count">0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="layui-col-sm12 layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-header">
                            <b>本月客户概览</b>
                        </div>
                        <div class="layui-card-body layuiadmin-card-list" >

                            <div class="layui-row customer-overview-component-container" >
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本月报价客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number" ><span id="this_month_quotation_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上月报价客户：<span id="last_month_quotation_customer_count">0</span></p>
                                    </div>
                                </div>
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本月新增客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_month_add_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上月新增客户：<span id="last_month_add_customer_count">0</span></p>
                                    </div>
                                </div>
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本月新成交客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_month_purchase_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上月新成交客户：<span id="last_month_purchase_customer_count">0</span></p>
                                    </div>
                                </div>
                                <div class="layui-col-md3">
                                    <div class="customer-overview-component-add">
                                        <p class="customer-overview-component-common-content-title">
                                            本月复购客户
                                            <span class="customer-overview-component-common-content-title-icon">
                                                <span class="helper-icon-container " aria-haspopup="true" aria-expanded="false">
                                                    <i class="layui-icon layui-icon-tips"></i>
                                                </span>
                                            </span>
                                        </p>
                                        <p class="customer-overview-component-common-content-number"><span id="this_month_repurchase_customer_count">0</span></p>
                                        <p class="customer-overview-component-common-content-recent-number">上月复购客户：<span id="last_month_repurchase_customer_count">0</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    layui.use(['jquery','element','form','table','laydate','echarts'], function(){
        var form = layui.form;
        var element = layui.element;
        var $ = layui.$;
        var laydate = layui.laydate;

        ajax_getCustomersStatistics();
        $('#customer_form .submit').on('click', function(){
            ajax_getCustomersStatistics();
        });

        function ajax_getCustomersStatistics() {
            var salesman_id = $('#salesman_id').val();
            var load = layer.load();
            $.get('{{ guard_url("statistic/get_customers_statistics") }}?salesman_id='+salesman_id).done(function (data) {
                layer.close(load);
                $("#this_week_add_customer_count").text(data.data.this_week_add_customer_count);
                $("#this_week_quotation_customer_count").text(data.data.this_week_quotation_customer_count);
                $("#this_week_purchase_customer_count").text(data.data.this_week_purchase_customer_count);
                $("#this_week_repurchase_customer_count").text(data.data.this_week_repurchase_customer_count);
                $("#last_week_add_customer_count").text(data.data.last_week_add_customer_count);
                $("#last_week_quotation_customer_count").text(data.data.last_week_quotation_customer_count);
                $("#last_week_purchase_customer_count").text(data.data.last_week_purchase_customer_count);
                $("#last_week_repurchase_customer_count").text(data.data.last_week_repurchase_customer_count);

                $("#this_month_add_customer_count").text(data.data.this_month_add_customer_count);
                $("#this_month_quotation_customer_count").text(data.data.this_month_quotation_customer_count);
                $("#this_month_purchase_customer_count").text(data.data.this_month_purchase_customer_count);
                $("#this_month_repurchase_customer_count").text(data.data.this_month_repurchase_customer_count);
                $("#last_month_add_customer_count").text(data.data.last_month_add_customer_count);
                $("#last_month_quotation_customer_count").text(data.data.last_month_quotation_customer_count);
                $("#last_month_purchase_customer_count").text(data.data.last_month_purchase_customer_count);
                $("#last_month_repurchase_customer_count").text(data.data.last_month_repurchase_customer_count);
            })
        }

    });
</script>