<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('mail_schedule.index') }}"><cite>{{ trans('mail_schedule.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.details') }}{{ trans('mail_schedule.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">

            </div>
            <div class="table-Box">
                <table class="layui-table" lay-filter="fb-table" id="fb-table">

                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/html" id="emailTpl">

    @{{#  if(d.status == 'failed'){ }}
    <span style="color:#FF5722">
    @{{#  } else { }}
        <span style="">
    @{{#  } }}
            @{{ d.email }}
    </span>
</script>
<script>
    var main_url = "{{guard_url('mail_schedule_report')}}";
    var delete_all_url = "{{guard_url('mail_schedule_report/destroyAll')}}";
    layui.use(['jquery','element','table'], function(){
        var $ = layui.$;
        var table = layui.table;
        var form = layui.form;
        var element = layui.element;
        table.render({
            elem: '#fb-table'
            ,url: '{{guard_url('mail_schedule_report')}}?mail_schedule_id={{ $mail_schedule->id }}'
            ,cols: [[
                {checkbox: true, fixed: true}
                ,{field:'id',title:'ID', width:80,sort:true}
                ,{field:'email',title:'{{ trans('mail_schedule_report.label.email') }}', width:220,templet:'#emailTpl'}
                ,{field:'sent_desc',title:'{{ trans('mail_schedule_report.label.sent') }}', width:100}
                ,{field:'status_desc',title:'{{ trans('mail_schedule_report.label.status') }}'}
                ,{field:'mail_account_username',title:'{{ trans('mail_schedule_report.label.mail_account_username') }}'}
                ,{field:'mail_template_name',title:'{{ trans('mail_schedule_report.label.mail_template_name') }}'}
                ,{field:'send_at',title:'{{ trans('mail_schedule_report.label.send_at') }}', width:180}
                ,{field:'mail_return',title:'{{ trans('mail_schedule_report.label.mail_return') }}'}
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
