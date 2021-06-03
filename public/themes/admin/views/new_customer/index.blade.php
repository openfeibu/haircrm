<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ guard_url('new_customer') }}"><cite>{{ trans('new_customer.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="tabel-message">
                <form class="layui-form" action="" lay-filter="fb-form">
                    <div class="layui-block mb10">
                        <div class="layui-inline tabel-btn">
                            <button class="layui-btn layui-btn-warm "  type="button"><a href="{{ guard_url('new_customer/create') }}">{{ trans('app.add') }} {{ trans('new_customer.name') }}</a></button>
                            <button class="layui-btn layui-btn-warm "  type="button"><a href="{{ guard_url('new_customer_import') }}">批量上传</a></button>
                            <button class="layui-btn layui-btn-primary " type="button" data-type="download" data-events="download">下载 Excel</button>
                            <button class="layui-btn layui-btn-primary " type="button" data-type="download_email_excel" data-events="download_email_excel">下载 Email Excel</button>
                            <button class="layui-btn layui-btn-primary " type="button" data-type="send_mail" data-events="send_mail">发送 Email</button>
                            <button class="layui-btn layui-btn-danger "  type="button" data-type="del" data-events="del">{{ trans('app.delete') }}</button>
                        </div>
                    </div>
                    <div class="layui-block table-search mb10">
                        <div class="layui-inline">
                            <select name="salesman_id" class="search_key layui-select">
                                @inject('salesmanRepository','App\Repositories\Eloquent\SalesmanRepository')
                                <option value="">{{ trans('salesman.name') }}</option>
                                @foreach($salesmanRepository->orderBy('name','asc')->orderBy('id','desc')->get() as $key => $salesman)
                                    <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="nickname" id="demoReload" placeholder="{{ trans('new_customer.label.nickname') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="email" id="demoReload" placeholder="{{ trans('new_customer.label.email') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="mobile" id="demoReload" placeholder="{{ trans('new_customer.label.mobile') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="ig" id="demoReload" placeholder="{{ trans('new_customer.label.ig') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="imessage" id="demoReload" placeholder="{{ trans('new_customer.label.imessage') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <input class="layui-input search_key" name="whatsapp" id="demoReload" placeholder="{{ trans('new_customer.label.whatsapp') }}" autocomplete="off">
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">过滤空{{ trans('new_customer.label.email') }}</label>
                            <input type="checkbox" class="search_key" name="email_not_null" placeholder="过滤空{{ trans('new_customer.label.email') }}" lay-skin="switch" lay-text="ON|OFF" value="0" lay-filter="email_not_null">
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">过滤空{{ trans('new_customer.label.mobile') }}</label>
                            <input type="checkbox" class="search_key" name="mobile_not_null" placeholder="过滤空{{ trans('new_customer.label.mobile') }}" lay-skin="switch" lay-text="ON|OFF" value="0" lay-filter="email_not_null">
                        </div>
                        <div class="layui-inline">
                            <button class="layui-btn" data-type="reload" type="button">{{ trans('app.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">

            </table>
        </div>
    </div>
</div>
<div class="new_customer_send_mail_content" style="display: none">
    <form class="layui-form send_mail_form" action="" style="margin: 10px 10px ">
        <div><p>计划发送共：<span id="mail_count">0</span>封</p></div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('mail_account.name') }}</label>
            <div class="layui-input-block">
                @inject('mailAccountRepository','App\Repositories\Eloquent\MailAccountRepository')
                @foreach($mailAccountRepository->orderBy('id','desc')->get() as $key => $account)
                <input type="checkbox" name="account_ids" title="{{ $account->username }}" value="{{ $account->id }}">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('mail_template.name') }}</label>
            <div class="layui-input-block">
                @inject('mailTemplateRepository','App\Repositories\Eloquent\MailTemplateRepository')
                @foreach($mailTemplateRepository->orderBy('id','desc')->get() as $key => $template)
                    <input type="checkbox" name="template_ids" title="{{ $template->name }}" value="{{ $template->id }}">
                @endforeach
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('mail_schedule.label.title') }} *</label>
            <div class="layui-input-inline">
                <input type="text" name="title" autocomplete="off" placeholder="请输入 {{ trans('mail_schedule.label.title') }}" class="layui-input" value="{{ config('model.mail.mail_schedule.title') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('mail_schedule.label.interval') }} *</label>
            <div class="layui-input-inline">
                <input type="text" name="interval" autocomplete="off" placeholder="请输入 {{ trans('mail_schedule.label.interval') }}" class="layui-input" value="{{ config('model.mail.mail_schedule.interval') }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('mail_schedule.label.per_hour_mail') }} *</label>
            <div class="layui-input-inline">
                <input type="text" name="per_hour_mail" autocomplete="off" placeholder="请输入 {{ trans('mail_schedule.label.per_hour_mail') }}" class="layui-input" value="{{ config('model.mail.mail_schedule.per_hour_mail') }}">
            </div>
        </div>
        <div class="layui-form-item fb-form-item2">
            <label class="layui-form-label">{{ trans('mail_template.label.active') }} *</label>

            <div class="layui-input-block">
                <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" class="active" checked>
            </div>

        </div>
    </form>
</div>
<script type="text/html" id="barDemo">
    @{{# if(d.mark == 'new'){ }}
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('customer/create') }}?new_customer_id=@{{ d.id }}">下单客户</a>
    @{{# } }}
    <a class="layui-btn layui-btn-sm" href="{{ guard_url('new_customer') }}/@{{ d.id }}">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>

<script>
    var main_url = "{{guard_url('new_customer')}}";
    var delete_all_url = "{{guard_url('new_customer/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        form.render();
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('new_customer')}}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80}
                ,{field:'salesman_name',title:'{{ trans('salesman.label.name') }}', width:120,sort:true}
                ,{field:'ig',title:'{{ trans('new_customer.label.ig') }}',templet:'<div><a href="https://www.instagram.com/@{{ d.ig }}" target="_blank">@{{ d.ig }}</a></div>'}
                ,{field:'ig_follower_count',title:'{{ trans('new_customer.label.ig_follower_count') }}',edit:'text', width:100}
                ,{field:'ig_sec',title:'{{ trans('new_customer.label.ig_sec') }}',templet:'<div>@{{# if(d.ig_sec){ }}<a href="https://www.instagram.com/@{{ d.ig_sec }}" target="_blank">@{{ d.ig_sec }}</a>@{{# }  }}</div>', width:120}
                ,{field:'mobile',title:'{{ trans('new_customer.label.mobile') }}', width:160,edit:'text'}
                ,{field:'whatsapp',title:'{{ trans('new_customer.label.whatsapp') }}', width:160,edit:'text'}
                ,{field:'imessage',title:'{{ trans('new_customer.label.imessage') }}', width:160,edit:'text'}
                ,{field:'email',title:'{{ trans('new_customer.label.email') }}',edit:'text'}
                ,{field:'facebook',title:'{{ trans('new_customer.label.facebook') }}',edit:'text'}
                ,{field:'nickname',title:'{{ trans('new_customer.label.nickname') }}',edit:'text'}
                ,{field:'company_website',title:'{{ trans('new_customer.label.company_website') }}',edit:'text'}
                ,{field:'company_name',title:'{{ trans('new_customer.label.company_name') }}',edit:'text'}
                ,{field:'main_product',title:'{{ trans('new_customer.label.main_product') }}',edit:'text'}
                ,{field:'mail_report_date',title:'{{ trans('mail_schedule_report.name') }}'}
                ,{field:'created_at',title:'{{ trans('app.created_at') }}'}
                ,{field:'mark_desc',title:'{{ trans('new_customer.label.mark') }}', width:100}
                ,{field:'remark',title:'{{ trans('new_customer.label.remark') }}', width:120,edit:'text', fixed: 'right'}
                ,{field:'score',title:'{{ trans('app.actions') }}', width:240, align: 'right',toolbar:'#barDemo', fixed: 'right'}
            ]]
            ,id: 'fb-table'
            ,page: true
            ,limit: '{{ config('app.limit') }}'
            ,height: 'full-200'
            ,cellMinWidth :'160'
            ,done:function () {
                element.init();
            }
        });
    });
</script>

{!! Theme::partial('common_handle_js') !!}
<script>
    layui.use(['jquery','element','table'], function() {
        var $ = layui.$;
        var table = layui.table;
        var element = layui.element;
        var form = layui.form;
        active.download = function () {
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = '{{ guard_url('new_customer_download') }}';
            var paramStr = "";
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?ids[]="+v.id;
                }else{
                    paramStr += "&ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            $(".search_key").each(function(){
                var name = $(this).attr('name');
                if(i == 0)
                {
                    paramStr += "?search["+name+"]="+$(this).val();
                }else{
                    paramStr += "&search["+name+"]="+$(this).val();
                }
                i++
            });
            var load =layer.load();
            window.location.href = url+paramStr;
            layer.close(load);

        }
        active.send_mail = function () {
            var load = layer.load();
            //判断有效邮箱数量
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var ajax_data = {'_token':"{!! csrf_token() !!}"};
            var i = 0;
            var count = 0;
            var ids = [];
            data.forEach(function(v){
                ids.push(v.id);
                count++;
            });
            $(".search_key").each(function(){
                var name = $(this).attr('name');
                ajax_data["search["+name+"]"] = $(this).val();
            });
            ajax_data['ids'] = ids;
            $.ajax({
                url : "{{ guard_url('new_customer/mail/count') }}",
                data : ajax_data,
                type : 'GET',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0) {
                        var mail_count = data.data.count;
                        $("#mail_count").html(mail_count);

                        layer.open({
                            type: 1,
                            shade: false,
                            title: '{{ trans('app.add') }}', //不显示标题
                            area: ['620px', '440px'], //宽高
                            content: $('.new_customer_send_mail_content'),
                            btn:['{{ trans('app.submit') }}'],
                            btn1:function()
                            {
                                var account_ids = [];
                                $('input[name=account_ids]:checked').each(function() {
                                    account_ids.push($(this).val());
                                });
                                var template_ids = [];
                                $('input[name=template_ids]:checked').each(function() {
                                    template_ids.push($(this).val());
                                });
                                var active = 0;
                                if($(".active").prop("checked")){
                                    active = 1;
                                }
                                if(account_ids.length === 0)
                                {
                                    layer.msg("请选择{{ trans('mail_account.name') }}");
                                    return false;
                                }
                                if(template_ids.length === 0)
                                {
                                    layer.msg("请选择{{ trans('mail_template.name') }}");
                                    return false;
                                }

                                ajax_data['active'] = active;
                                ajax_data['account_ids'] = account_ids;
                                ajax_data['template_ids'] = template_ids;
                                ajax_data['interval'] = $('input[name=interval]').val();
                                ajax_data['per_hour_mail'] = $('input[name=per_hour_mail]').val();
                                ajax_data['title'] = $('input[name=title]').val();
                                var load =layer.load();
                                $.ajax({
                                    url : "{{ guard_url('mail_schedule/send/new_customer') }}",
                                    data : ajax_data,
                                    type : 'POST',
                                    success : function (data) {
                                        layer.close(load);
                                        if(data.code == 0) {
                                            window.location.href=data.url;
                                        }else{
                                            layer.msg(data.msg);
                                        }
                                    },
                                    error : function (jqXHR, textStatus, errorThrown) {
                                        layer.close(load);
                                        $.ajax_error(jqXHR, textStatus, errorThrown);
                                    }
                                });
                            }
                        });
                    }else{
                        layer.msg(data.msg);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });


        }
        active.download_email_excel = function () {
            var load = layer.load();
            //判断有效邮箱数量
            var checkStatus = table.checkStatus('fb-table')
                    ,data = checkStatus.data;
            var data_id_obj = {};
            var i = 0;
            var url = '{{ guard_url('new_customer_download_email_excel') }}';
            var paramStr = "";
            data.forEach(function(v){
                if(i == 0)
                {
                    paramStr += "?ids[]="+v.id;
                }else{
                    paramStr += "&ids[]="+v.id;
                }
                data_id_obj[i] = v.id; i++
            });
            $(".search_key").each(function(){
                var name = $(this).attr('name');
                if(i == 0)
                {
                    paramStr += "?search["+name+"]="+$(this).val();
                }else{
                    paramStr += "&search["+name+"]="+$(this).val();
                }
                i++
            });
            var load =layer.load();
            window.location.href = url+paramStr;
            layer.close(load);


        }

        form.on('switch(email_not_null)', function(data) {
            console.log(this.checked);
            $(data.elem).attr('type', 'hidden').val(this.checked ? 1 : 0);
        });

    })
</script>