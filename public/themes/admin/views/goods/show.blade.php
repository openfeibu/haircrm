<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('goods.index') }}"><cite>{{ trans('goods.title') }}</cite></a><span lay-separator="">/</span>
            <a><cite>{{ trans('app.edit') }}{{ trans('goods.name') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('goods/'.$goods->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('goods.label.name') }} *</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" autocomplete="off" placeholder="" lay-verify="required"  class="layui-input" value="{{ $goods->name }}" >
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('goods.label.purchase_price') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="goods_purchase_price" autocomplete="off" placeholder="" class="layui-input" value="{{ $goods->purchase_price }}">
                        </div>
                    </div>
                    <div class="layui-form-item fb-form-item2">
                        <label class="layui-form-label">{{ trans('goods.label.selling_price') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="goods_selling_price" autocomplete="off" placeholder="" class="layui-input" value="{{ $goods->selling_price }}">
                        </div>
                    </div>
                    @if($goods->attribute_id && $attribute)
                        <div class="layui-form-item fb-form-item2">
                            <label class="layui-form-label">选择{{ $attribute['name'] }} *</label>
                            <div class="fb-form-item-box fb-clearfix">
                                @foreach($attribute_values as $key => $attribute_value)
                                    <div class="layui-input-block">
                                        @if(in_array($attribute_value['id'],$goods->attr_value_id_arr))
                                            <input type="checkbox" name="attribute_value[{{ $attribute_value['id'] }}]" lay-skin="primary" title="{{ $attribute_value['value'] }}" checked="">
                                            <input type="text" name="purchase_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.purchase_price') }}" class="layui-input minInput" value="{{ $goods_attribute_values[$attribute_value['id']]['purchase_price'] }}">
                                            <input type="text" name="selling_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.selling_price') }}" class="layui-input minInput" value="{{ $goods_attribute_values[$attribute_value['id']]['selling_price'] }}">
                                        @else
                                            <input type="checkbox" name="attribute_value[{{ $attribute_value['id'] }}]" lay-skin="primary" title="{{ $attribute_value['value'] }}" checked="">
                                            <input type="text" name="purchase_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.purchase_price') }}" class="layui-input minInput">
                                            <input type="text" name="selling_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.selling_price') }}" class="layui-input minInput">
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
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



