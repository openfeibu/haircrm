<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('new_customer.index') }}"><cite>{{ trans('new_customer.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.add') }} {{ trans('new_customer.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('new_customer')}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">业务员 *</label>
                        <div class="layui-input-block">
                            <select name="salesman_id" lay-filter="checkBox" lay-verify="required" lay-search>
                                @foreach($salesmen as $key => $salesman)
                                    <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!--
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.mark') }} *</label>
                        <div class="layui-input-block">
                            <select name="mark" lay-filter="checkBox" lay-verify="required">
                                @foreach(trans('new_customer.mark') as $key => $mark)
                                    <option value="{{ $key }}">{{ $mark }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    -->
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.company_name') }}</label>

                        <div class="layui-input-block">
                            <input type="text" name="company_name" autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.company_name') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.company_website') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="company_website"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.company_website') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.nickname') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="nickname" autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.nickname') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.email') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="email"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.email') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.mobile') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="mobile"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.mobile') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.imessage') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="imessage"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.imessage') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.whatsapp') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="whatsapp"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.whatsapp') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.main_product') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="main_product"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.main_product') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.ig') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="ig"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.ig') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.ig_follower_count') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="ig_follower_count"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.ig_follower_count') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.ig_sec') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="ig_sec"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.ig_sec') }}" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.facebook') }}</label>
                        <div class="layui-input-block">
                            <input type="text" name="facebook"  autocomplete="off" placeholder="请输入 {{ trans('new_customer.label.facebook') }}" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('new_customer.label.remark') }}</label>
                        <div class="layui-input-block">
                            <textarea name="remark" id="remark" placeholder="请输入 {{ trans('new_customer.label.remark') }}" class="layui-textarea"></textarea>
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

