<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('mail_account.index') }}"><cite>{{ trans('mail_account.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }}{{ trans('mail_account.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('mail_account')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.host') }} *</label>

                        <div class="layui-input-block">
                            <select name="host" lay-filter="checkBox">
                                @foreach(trans('mail_account.host') as $key => $host)
                                    <option value="{{ $host }}">{{ $host }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.port') }} *</label>

                        <div class="layui-input-block">
                            <select name="port" lay-filter="checkBox">
                                @foreach(trans('mail_account.port') as $key => $port)
                                    <option value="{{ $port }}">{{ $port }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.username') }} *</label>

                        <div class="layui-input-block">
                            <input type="text" name="username" lay-verify="email" autocomplete="off" placeholder="请输入{{ trans('mail_account.label.username') }}" class="layui-input" value="{{ $mail_account->username }}">
                        </div>

                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.password') }} *</label>

                        <div class="layui-input-block">
                            <input type="text" name="password" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('mail_account.label.password') }}" class="layui-input" value="{{ $mail_account->password }}">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.from_address') }} *</label>

                        <div class="layui-input-block">
                            <input type="text" name="from_address" lay-verify="email" autocomplete="off" placeholder="请输入{{ trans('mail_account.label.from_address') }}" class="layui-input" value="{{ $mail_account->from_address }}" >
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.from_name') }} *</label>

                        <div class="layui-input-block">
                            <input type="text" name="from_name" lay-verify="required" autocomplete="off" placeholder="请输入{{ trans('mail_account.label.from_name') }}" class="layui-input" value="{{ config('model.mail.mail_account.from_name') }}">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.encryption') }} *</label>

                        <div class="layui-input-block">
                            <select name="encryption" lay-filter="checkBox">
                                @foreach(trans('mail_account.encryption') as $key => $encryption)
                                    <option value="{{ $encryption }}">{{ $encryption }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.address') }}</label>

                        <div class="layui-input-block">
                            <input type="text" name="address" autocomplete="off" placeholder="请输入{{ trans('mail_account.label.address') }}" class="layui-input" value="{{ $mail_account->address }}">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('mail_account.label.name') }}</label>

                        <div class="layui-input-block">
                            <input type="text" name="name" autocomplete="off" placeholder="请输入{{ trans('mail_account.label.name') }}" class="layui-input" value="{{ $mail_account->name }}">
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

