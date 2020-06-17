<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('customer.index') }}"><cite>{{ trans('customer.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}{{ trans('customer.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('customer/'.$customer->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">业务员 *</label>
                        <div class="layui-input-block">
                            <select name="salesman_id" lay-filter="checkBox" lay-verify="required">
                                <option value="">请选择业务员</option>
                                @foreach($salesmen as $key => $salesman)
                                    <option value="{{ $salesman->id }}" @if($salesman->id == $customer->salesman_id) selected @endif>{{ $salesman->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">客户名称 *</label>

                        <div class="layui-input-block">
                            <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="请输入客户名称" class="layui-input" value="{{ $customer->name }}">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">IG号</label>

                        <div class="layui-input-block">
                            <input type="text" name="ig" lay-verify="title" autocomplete="off" placeholder="请输入IG号" class="layui-input" value="{{ $customer->ig }}">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">手机号</label>

                        <div class="layui-input-block">
                            <input type="text" name="mobile" autocomplete="off" placeholder="请输入手机号" class="layui-input" value="{{ $customer->mobile }}">
                        </div>

                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">邮箱</label>

                        <div class="layui-input-block">
                            <input type="text" name="email"  autocomplete="off" placeholder="请输入邮箱" class="layui-input" value="{{ $customer->email }}">
                        </div>

                    </div>

                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">imessage</label>

                        <div class="layui-input-block">
                            <input type="text" name="imessage" autocomplete="off" placeholder="请输入imessage" class="layui-input" value="{{ $customer->imessage }}">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">whatsapp</label>

                        <div class="layui-input-block">
                            <input type="text" name="whatsapp" autocomplete="off" placeholder="请输入whatsapp" class="layui-input" value="{{ $customer->whatsapp }}">
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('freight_area.name') }}</label>

                        <div class="layui-input-block">
                            <select name="area_code" lay-filter="checkBox">
                                @inject('freight_area','App\Models\FreightArea')
                                @foreach($freight_area->orderBy('order','asc')->orderBy('code','asc')->get() as $key => $freight_area)
                                    <option value="{{ $freight_area->code }}" @if($freight_area->code == $customer->area_code) selected @endif>{{ $freight_area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">地址</label>

                        <div class="layui-input-block">
                            <textarea name="address" placeholder="请输入地址" class="layui-textarea">{!! $customer->address !!}</textarea>
                        </div>

                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">客户来源</label>
                        <div class="layui-input-block">
                            <input type="text" name="from" autocomplete="off" placeholder="请输入客户来源" class="layui-input" value="{{ $customer->from }}">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('customer.label.level') }}</label>

                        <div class="layui-input-block">
                            <select name="level" lay-filter="checkBox">
                                @foreach(config('model.customer.customer.level') as $key => $level)
                                    <option value="{{ $level }}" @if($customer->level == $level)selected @endif>{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="_method" value="PUT">
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

