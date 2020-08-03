<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('mail_schedule.index') }}"><cite>{{ trans('mail_schedule.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}{{ trans('mail_schedule.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('mail_schedule')}}" method="post" lay-filter="fb-form">

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('app.title') }} *</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('app.title') }}" class="layui-input" value="{{ $mail_schedule->title }}">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_schedule.label.interval') }} *</label>
                        <div class="layui-input-block">
                            <input type="text" name="interval" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('mail_schedule.label.interval') }}" class="layui-input" value="{{ config('model.mail.mail_schedule.interval') }}">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_schedule.label.per_hour_mail') }} *</label>
                        <div class="layui-input-block">
                            <input type="text" name="per_hour_mail" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('mail_schedule.label.per_hour_mail') }}" class="layui-input" value="{{ config('model.mail.mail_schedule.per_hour_mail') }}">
                        </div>
                    </div>


                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('mail_account.name') }} *</label>

                        <div class="layui-input-block">
                            @inject('mailAccountRepository','App\Repositories\Eloquent\MailAccountRepository')
                            @foreach( $mailAccountRepository->getAll() as $key => $mail_account)
                            <input type="checkbox" name="mail_accounts[]"  title="{{ $mail_account->username }}" value="{{ $mail_account->id }}">
                            @endforeach

                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('mail_template.name') }} *</label>

                        <div class="layui-input-block">
                            @inject('mailTemplateRepository','App\Repositories\Eloquent\MailTemplateRepository')
                            @foreach( $mailTemplateRepository->getAll() as $key => $mail_template)
                                <input type="checkbox" name="mail_templates[]" title="{{ $mail_template->name }}" value="{{ $mail_template->id }}">
                            @endforeach

                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_schedule.label.active') }} *</label>

                        <div class="layui-input-block">
                            <input type="checkbox" name="active" value="1" lay-skin="switch" lay-text="是|否" lay-filter="active" checked>
                        </div>

                    </div>

                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-submit" lay-submit="" lay-filter="demo1">立即提交</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                </form>
            </div>

        </div>
    </div>
</div>

