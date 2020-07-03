<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('mail_template.index') }}"><cite>{{ trans('mail_template.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}{{ trans('mail_template.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('mail_template/'.$mail_template->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_template.label.name') }} *</label>

                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('mail_template.label.name') }}" class="layui-input" value="{{ $mail_template->name }}">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_template.label.subject') }} *</label>

                        <div class="layui-input-block">
                            <input type="text" name="subject" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('mail_template.label.subject') }}" class="layui-input" value="{{ $mail_template->subject }}">
                        </div>
                    </div>

                    <div class="layui-form-item layui-form-text">
                        <label class="layui-form-label">{{ trans('mail_template.label.content') }} *</label>
                        <div class="layui-input-block">
                            <script type="text/plain" id="content" name="content" style="width:1000px;height:240px;">
                                {!! $mail_template->content !!}
                            </script>
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_template.label.active') }} *</label>

                        <div class="layui-input-block">
                            <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" checked>
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    <input type="hidden" name="_method" value="PUT">
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>

{!! Theme::asset()->container('ueditor')->scripts() !!}
<script>
    var ue = getUe();
</script>