<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('salesman.index') }}"><cite>{{ trans('salesman.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}{{ trans('salesman.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('salesman')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.email") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="email" value="{{ $salesman->email }}" lay-verify="email|required" autocomplete="off" placeholder="请输入{{ trans("salesman.label.email") }}" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.name") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="name" value="{{ $salesman->name }}" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans("salesman.label.name") }}" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.en_name") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="en_name" value="{{ $salesman->en_name }}" lay-verify="required"  autocomplete="off" placeholder="请输入{{ trans("salesman.label.en_name") }}" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.entry_date") }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="entry_date" value="{{ $salesman->entry_date }}"  autocomplete="off" placeholder="请输入{{ trans("salesman.label.entry_date") }}" class="layui-input" id="entry_date" >
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("admin_user.label.password") }} *</label>
                        <div class="layui-input-inline">
                            <input type="password" name="password" placeholder="请输入{{ trans("admin_user.label.password") }}" autocomplete="off" class="layui-input"  lay-verify="required" >
                        </div>
                        <div class="layui-form-mid layui-word-aux">请输入密码，至少六位数</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.ig") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="ig" placeholder="请输入{{ trans("salesman.label.ig") }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.imessage") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="imessage" placeholder="请输入{{ trans("salesman.label.imessage") }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.mobile") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="mobile" placeholder="请输入{{ trans("salesman.label.mobile") }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.monthly_performance_target") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="monthly_performance_target" placeholder="请输入{{ trans("salesman.label.monthly_performance_target") }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.yearly_performance_target") }} *</label>
                        <div class="layui-input-inline">
                            <input type="text" name="yearly_performance_target" placeholder="请输入{{ trans("salesman.label.yearly_performance_target") }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("salesman.label.active") }}</label>
                        <div class="layui-input-inline">
                            <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" checked>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans("admin_user.label.roles") }} *</label>
                        <div class="layui-input-block">
                            <?php $i=1 ?>
                            @foreach($roles as $key => $role)
                                <input type="radio" name="roles[]" value="{{ $role->id }}" title="{{ $role->name }}" @if($i == 1) checked @endif lay-verify="required">
                                <?php $i++ ?>
                            @endforeach
                        </div>
                    </div>
                    {!!Form::token()!!}
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                </form>
            </div>


        </div>
    </div>
</div>

<script>
    layui.use(['jquery','element','table','laydate'], function () {
        var laydate= layui.laydate,
                form = layui.form,
                $ = layui.jquery,
                layer = layui.layer;

        laydate.render({
            elem: '#entry_date'
            ,type: 'date'
            ,value:"{!! date('Y-m-d') !!}"
        });
    });
</script>

<script>
</script>