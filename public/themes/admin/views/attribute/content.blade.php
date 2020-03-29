<label class="layui-form-label">选择{{ $attribute['name'] }} *</label>
<div class="fb-form-item-box fb-clearfix">
    @foreach($attribute_values as $key => $attribute_value)
        <div class="layui-input-block">
            <input type="checkbox" name="attribute_value[{{ $attribute_value['id'] }}]" lay-skin="primary" title="{{ $attribute_value['value'] }}" checked="">
            <input type="text" name="purchase_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.purchase_price') }}" class="layui-input minInput">
            <input type="text" name="selling_price[{{ $attribute_value['id'] }}]" lay-verify="title" autocomplete="off" placeholder="{{ trans('goods.label.selling_price') }}" class="layui-input minInput">
        </div>
    @endforeach
</div>