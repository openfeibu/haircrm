<div class="main">
    {!! Theme::widget('breadcrumb')->render() !!}
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12">
            <div class="fb-main-table">
                <form class="layui-form" action="{{guard_url('banner/'.$banner->id)}}" method="post" lay-filter="fb-form">
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('app.title') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="title" value="{{ $banner->title }}" lay-verify="title" autocomplete="off" placeholder="请输入{{ trans('app.title') }}" class="layui-input" >
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('app.image') }}</label>
                        {!! $banner->files('image')
                        ->url($banner->getUploadUrl('image'))
                        ->uploader()!!}
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('banner.label.url') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="url" value="{{ $banner->url }}" placeholder="请输入{{ trans('banner.label.url') }}" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('app.order') }}</label>
                        <div class="layui-input-inline">
                            <input type="text" name="order" autocomplete="off" placeholder="" class="layui-input" value="{{$banner['order']}}" lay-verify="number">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label class="layui-form-label">{{ trans('app.type') }}</label>
                        <div class="layui-input-inline">
                            <select name="type" lay-filter="aihao">
                                @foreach(config('model.banner.banner.type') as $type)
                                    <option value="{{ $type }}" @if($banner['type'] == $type) selected="" @endif>{{ trans('banner.type.'.$type) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn" lay-submit="" lay-filter="demo1">{{ trans('app.submit_now') }}</button>
                        </div>
                    </div>
                    {!!Form::token()!!}
                    <input type="hidden" name="_method" value="PUT">
                </form>
            </div>

        </div>
    </div>
</div>


