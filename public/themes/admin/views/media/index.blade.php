<div class="main">
    <div class="layui-card fb-minNav">
        <div class="layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
            <a href="{{ guard_url('home') }}">{{ trans('app.home') }}</a><span lay-separator="">/</span>
            <a href="{{ route('media.index') }}"><cite>{{ trans('media.title') }}</cite></a>
        </div>
    </div>
    <div class="main_full">
        {!! Theme::partial('message') !!}
        <div class="layui-col-md12 photos">
            <div class="tabel-message">
                <div class="layui-inline tabel-btn">
                    <button class="layui-btn layui-btn-warm " data-type="add" data-events="add"><i class="layui-icon layui-icon-folder"></i>{{ trans('app.add_folder') }}</button>
                    <button class="layui-btn layui-btn-warm" id="upload_image"><i class="layui-icon layui-icon-upload"></i>{{ trans('app.upload') }}</button>
                </div>
                <!--
                <div class="layui-inline">
                    <input class="layui-input search_key" name="title" id="demoReload" placeholder="搜索标题" autocomplete="off">
                </div>
                <button class="layui-btn" data-type="reload">搜索</button>
                -->
            </div>
            <div class="media-nav layui-breadcrumb" lay-filter="breadcrumb" style="visibility: visible;">
                <div class="layui-card-header">
                <a href="{{ route('media.index') }}"><i class="layui-icon layui-icon-menu"></i></a>
                {!! $nav !!}
                </div>
            </div>

            <table id="fb-table" class="layui-table"  lay-filter="fb-table">
                <thead>
                    <th lay-data="{field:'id',hide:'true'}">ID/th>
                    <th lay-data="{field:'name',toolbar:'#nameDemo',edit:true}">{{ trans('app.name') }}</th>
                    <th lay-data="{field:'description'}">{{ trans('media.label.description') }}</th>
                    <th lay-data="{field:'url'}">{{ trans('media.label.url') }}</th>
                    <th lay-data="{field:'created_at'}">{{ trans('app.created_at') }}</th>
                    <th lay-data="{field:'type',hide:'true'}">{{ trans('media.label.type') }}</th>
                    <th lay-data="{field:'score',align:'right',toolbar:'#barDemo'}">{{ trans('app.actions') }}</th>
                </thead>
                <tbody>
                    @foreach($folders as $folder_key => $folder)
                    <tr class="layui-table-click">
                        <td>{{ $folder['id'] }}</td>
                        <td>{{ $folder['name'] }}</td>
                        <td>{{ $folder['description'] }}</td>
                        <td>{{ $folder['url'] }}</td>
                        <td>{{ $folder['created_at'] }}</td>
                        <td>{{ $folder['type'] }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(count($media_list))
            <table id="media-table" class="layui-table"  lay-filter="media-table" >
                <thead>
                <th lay-data="{field:'id',hide:'true'}">ID/th>
                <th lay-data="{field:'name',toolbar:'#mediaDemo',edit:true}">{{ trans('app.file') }}</th>
                <th lay-data="{field:'description'}">{{ trans('media.label.description') }}</th>
                <th lay-data="{field:'url'}">{{ trans('media.label.url') }}</th>
                <th lay-data="{field:'created_at'}">{{ trans('app.created_at') }}</th>
                <th lay-data="{field:'score',align:'right',toolbar:'#mediaBar'}">{{ trans('app.actions') }}</th>
                </thead>
                <tbody>
                @foreach($media_list as $media_key => $media)
                    <tr class="layui-table-click">
                        <td>{{ $media['id'] }}</td>
                        <td>{{ $media['name'] }}</td>
                        <td>{{ $media['description'] }}</td>
                        <td>{{ $media['url'] }}</td>
                        <td>{{ $media['created_at'] }}</td>
                        <td></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
</div>
<div class="add_folder_content" style="display: none">
    <form class="layui-form folder_create_form" action="" style="margin: 10px 10px ">
        <input type="hidden" name="folder_id" id="folder_id" value="{{ $folder_id }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('media.label.name') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="name" required  lay-verify="required" placeholder="{{ trans('app.English') }}" autocomplete="off" class="layui-input name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('media.label.description') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="description" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input description">
            </div>
        </div>
    </form>
</div>
<div class="edit_folder_content" style="display: none">
    <form class="layui-form folder_edit_form" action="" style="margin: 10px 10px ">
        <input type="hidden" name="folder_id" id="folder_id" value="{{ $folder_id }}">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('media.label.name') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="name" required  lay-verify="required" placeholder="{{ trans('app.English') }}" autocomplete="off" class="layui-input name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('media.label.description') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="description" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input description">
            </div>
        </div>
    </form>
</div>
<div class="edit_media_content" style="display: none">
    <form class="layui-form media_edit_form" action="" style="margin: 10px 10px ">
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('media.label.name') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="name" required  lay-verify="required" placeholder="{{ trans('app.English') }}" autocomplete="off" class="layui-input name">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">{{ trans('media.label.description') }}</label>
            <div class="layui-input-inline">
                <input type="text" name="description" required lay-verify="required" placeholder="" autocomplete="off" class="layui-input description">
            </div>
        </div>
    </form>
</div>
<script type="text/html" id="barDemo">
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    @{{#  if(d.type != 'system'){ }}
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
    @{{#  } }}
</script>
<script type="text/html" id="nameDemo">
    <a href="{{ guard_url('media') }}?folder_id=@{{ d.id }}"><i class="layui-icon layui-icon-folder"></i> @{{ d.name }}</a>
</script>
<script type="text/html" id="mediaDemo">
    <img src="{{ url('image/original/') }}@{{d.url}}" alt="" height="28" layer-src="{{ url('image/original/') }}@{{d.url}}" alt="@{{ d.name }}" >
</script>
<script type="text/html" id="mediaBar">
    <a class="layui-btn layui-btn-sm" lay-event="edit">{{ trans('app.edit') }}</a>
    <a class="layui-btn layui-btn-danger layui-btn-sm" lay-event="del">{{ trans('app.delete') }}</a>
</script>
<script>
    layui.use(['jquery','element','table','upload'], function(){
        var table = layui.table;
        var form = layui.form;
        var upload = layui.upload;
        var $ = layui.$;

        table.init('fb-table', {
            skin:'nob',
        });

        table.on('tool(fb-table)', function(obj){
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('{{ trans('messages.confirm_delete') }}', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('media_folder/destroy') }}"+'?url='+data.url,
                        data : data,
                        type : 'delete',
                        success : function (data) {
                            layer.close(load);
                            if(data.code != 0)
                            {
                                layer.msg(data.msg);
                            }else{
                                obj.del();
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            } else if(obj.event === 'edit'){
                var form = $('.folder_edit_form');
                form.find('.name').val(data.name);
                form.find('.description').val(data.description);
                layer.open({
                    type: 1,
                    shade: false,
                    title: '{{ trans('app.edit') }}', //不显示标题
                    area: ['420px', '240px'], //宽高
                    content: $('.edit_folder_content'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {
                        var load = layer.load();
                        $.ajax({
                            url : "{{ guard_url('media_folder/update') }}/"+data.id,
                            data : {'name':form.find('.name').val(),'description':form.find('.description').val(),'_token':"{!! csrf_token() !!}"},
                            type : 'PUT',
                            success : function (data) {
                                layer.close(load);
                                if(data.code == 0) {
                                    window.location.reload();
                                }else{
                                    layer.msg(data.msg);
                                }
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                });
            }
        });
        table.on('tool(media-table)', function(obj){
            var data = obj.data;
            data['_token'] = "{!! csrf_token() !!}";
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('{{ trans('messages.confirm_delete') }}', function(index){
                    layer.close(index);
                    var load = layer.load();
                    $.ajax({
                        url : "{{ guard_url('media/destroy') }}"+'?url='+data.url,
                        data : data,
                        type : 'delete',
                        success : function (data) {
                            layer.close(load);
                            if(data.code != 0)
                            {
                                layer.msg(data.msg);
                            }else{
                                obj.del();
                            }
                        },
                        error : function (jqXHR, textStatus, errorThrown) {
                            layer.close(load);
                            $.ajax_error(jqXHR, textStatus, errorThrown);
                        }
                    });
                });
            } else if(obj.event === 'edit'){
                var form = $('.folder_edit_form');
                form.find('.name').val(data.name);
                form.find('.description').val(data.description);
                layer.open({
                    type: 1,
                    shade: false,
                    title: '{{ trans('app.edit') }}', //不显示标题
                    area: ['420px', '240px'], //宽高
                    content: $('.edit_folder_content'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {
                        var load = layer.load();
                        $.ajax({
                            url : "{{ guard_url('media/update') }}/"+data.id,
                            data : {'name':form.find('.name').val(),'description':form.find('.description').val(),'_token':"{!! csrf_token() !!}"},
                            type : 'PUT',
                            success : function (data) {
                                layer.close(load);
                                if(data.code == 0) {
                                    window.location.reload();
                                }else{
                                    layer.msg(data.msg);
                                }
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                });
            }
        });
        var $ = layui.$, active = {
            add:function(){
                layer.open({
                    type: 1,
                    shade: false,
                    title: '{{ trans('app.add') }}', //不显示标题
                    area: ['420px', '240px'], //宽高
                    content: $('.add_folder_content'),
                    btn:['{{ trans('app.submit') }}'],
                    btn1:function()
                    {
                        var load = layer.load();
                        var form = $('.folder_create_form');
                        $.ajax({
                            url : "{{ route('media_folder.store') }}",
                            data : {'name':form.find('.name').val(),'description':form.find('.description').val(),'folder_id':'{{ $folder_id }}','_token':"{!! csrf_token() !!}"},
                            type : 'POST',
                            success : function (data) {
                                layer.close(load);
                                if(data.code == 0) {
                                    window.location.reload();
                                }else{
                                    layer.msg(data.msg);
                                }
                            },
                            error : function (jqXHR, textStatus, errorThrown) {
                                layer.close(load);
                                $.ajax_error(jqXHR, textStatus, errorThrown);
                            }
                        });
                    }
                });


            },
            del:function(){
                var checkStatus = table.checkStatus('fb-table')
                        ,data = checkStatus.data;
                var data_id_obj = {};
                var i = 0;
                data.forEach(function(v){ data_id_obj[i] = v.id; i++});
                data.length == 0 ?
                        layer.msg('请选择要删除的数据', {
                            time: 2000 //2秒关闭（如果不配置，默认是3秒）
                        })
                        :
                        layer.confirm('是否删除已选择的数据',{title:'提示'},function(index){
                            layer.close(index);
                            var load = layer.load();
                            $.ajax({
                                url : delete_all_url,
                                data :  {'ids':data_id_obj,'_token' : "{!! csrf_token() !!}"},
                                type : 'POST',
                                success : function (data) {
                                    var nPage = $(".layui-laypage-curr em").eq(1).text();
                                    //执行重载
                                    table.reload('fb-table', {
                                        page: {
                                            curr: nPage //重新从第 1 页开始
                                        }
                                    });
                                    layer.close(load);
                                },
                                error : function (jqXHR, textStatus, errorThrown) {
                                    layer.close(load);
                                    $.ajax_error(jqXHR, textStatus, errorThrown);
                                }
                            });
                        })  ;

            }
        };
        upload.render({
            elem: '#upload_image'
            ,accept:'images'
            ,url: '{!! guard_url('media/upload') !!}'
            ,data: {
                '_token':$('meta[name="csrf-token"]').attr('content'),
                'folder_id':"{{ $folder_id }}"
            }
            ,before: function(obj){ //obj参数包含的信息，跟 choose回调完全一致，可参见上文。
                layer.load(); //上传loading
            }
            ,done: function(res, index, upload){
                console.log(res)
                layer.closeAll('loading'); //关闭loading
                layer.msg(res.message);
                window.location.reload();
            }
            ,error: function(index, upload){
                layer.closeAll('loading'); //关闭loading
            }
        });
        table.init('media-table', {
            skin:'nob',
            //height:'100px',
        });
        $(".layui-form").eq(1).find('.layui-table .layui-table-cell > span').css({'font-weight': 'bold'});//表头字体样式
        /*$('th').css({'background-color': '#5792c6', 'color': '#fff','font-weight':'bold'}) 表头的样式 */
        $(".layui-form").eq(1).find('th').hide();//表头隐藏的样式
        $(".layui-form").eq(1).find('.layui-table-page').css('margin-top','40px');//页码部分的高度调整

        $('.tabel-message .layui-btn').on('click', function(){
            var type = $(this).data('type');
            active[type] ? active[type].call(this) : '';
        });
        layer.photos({
            photos: '.photos'
            ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机（请注意，3.0之前的版本用shift参数）
        });
    });
</script>

