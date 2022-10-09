<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('supplier.index') }}"><cite>{{ trans('supplier.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }} {{ trans('supplier.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('supplier')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('supplier.label.name') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="name"  autocomplete="off" placeholder="请输入 {{ trans('supplier.label.name') }}" class="layui-input check_exist">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('supplier.label.code') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="code"  autocomplete="off" placeholder="请输入 {{ trans('supplier.label.code') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('supplier.label.url') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="url"  autocomplete="off" placeholder="请输入 {{ trans('supplier.label.url') }}" class="layui-input">
                        </div>
                    </div>


                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('supplier.label.remark') }}</label>
                        <div class="layui-input-block">
                            <textarea name="remark" id="remark" placeholder="请输入 {{ trans('supplier.label.remark') }}" class="layui-textarea"></textarea>
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" id="submit-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>

<script>

    layui.use(['element',"table",'form',"jquery"], function() {
        var form = layui.form;
        var table = layui.table;
        var $ = layui.$;
        var submit = true;


    });

</script>