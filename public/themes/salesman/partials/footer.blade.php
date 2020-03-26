<script>
    layui.use(['element','form','jquery'], function(){
        var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块
        var $ = layui.$;
        var form = layui.form;
        //监听导航点击
        element.on('nav(demo)', function(elem){
            //console.log(elem)
            layer.msg(elem.text());
        });
        //自定义验证规则
        form.verify({
            otherReq: function(value,item){
                var $ = layui.$;
                var verifyName=$(item).attr('name')
                        , verifyType=$(item).attr('type')
                        ,formElem=$(item).parents('.layui-form')//获取当前所在的form元素，如果存在的话
                        ,verifyElem=formElem.find('input[name='+verifyName+']')//获取需要校验的元素
                        ,isTrue= verifyElem.is(':checked')//是否命中校验
                        ,focusElem = verifyElem.next().find('i.layui-icon');//焦点元素
                if(!isTrue || !value){
                    //定位焦点
                    focusElem.css(verifyType=='radio'?{"color":"#FF5722"}:{"border-color":"#FF5722"});
                    //对非输入框设置焦点
                    focusElem.first().attr("tabIndex","1").css("outline","0").blur(function() {
                        focusElem.css(verifyType=='radio'?{"color":""}:{"border-color":""});
                    }).focus();
                    return '必填项不能为空';
                }
            }
        });
    });

</script>