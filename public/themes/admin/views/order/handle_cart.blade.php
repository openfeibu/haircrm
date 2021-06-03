<script>
    var freight_config = eval({!! json_encode(freight_config()) !!});
    var freight_area_code = "{!! $freight_area_code !!}";
    layui.use(['element',"table",'form',"jquery"], function(){
        var form = layui.form;
        var table = layui.table;
        var upload = layui.upload;
        var $ = layui.$;

        table.on('edit(cart)', function(obj){
            var data = obj.data;
            var value = obj.value //得到修改后的值
                    ,data = obj.data //得到所在行所有键值
                    ,field = obj.field; //得到字段
            switch(field)
            {
                case 'number':
                    handle_number();
                    break;
                case 'selling_price':
                    handle_number();
                    break;
                case 'purchase_price':
                    handle_number();
                    break;
                case 'weight':
                    handle_number();
                    break;
                default:

            }
        });
        form.on('checkbox', function(obj){
            var check = $(obj.othis).hasClass("layui-form-checked");
            if(check){
                $(obj.othis).parents(".layui-input-block").find(".numInput").show()
            }else{
                $(obj.othis).parents(".layui-input-block").find(".numInput").hide()

            }
        });

        $.onNodeClick = function (node) {
            var tableData = layui.table.cache.cart;
            //用于判断，购物车中是否已有该商品
            var flag = false;
            if(tableData != null)
            {
                for (var i=0;i<tableData.length;i++)
                {
                    if(tableData[i]['list_id'] == node.list_id)
                    {
                        var number = tableData[i]['number'];
                        number++;
                        tableData[i]['number'] = number;
                        flag = true;
                    }else{
                        flag = flag ? flag : false;
                    }
                }
                if(flag == true)
                {
                    table.reload("cart",{data:tableData});
                    handle_number();
                }else{
                    appendTbody(node)
                }
            }else{
                appendTbody(node)
            }
        }

        function handle_number() {
            var tableData = layui.table.cache.cart;
            var number = 0,weight = 0,selling_price = 0,purchase_price = 0,total = 0,paypal_fee = 0,freight = 0;
            var freight_arr = {};
            var last_freight_category_id = 0;
            var freight_category_id = 0;
            for (var i=0;i<tableData.length;i++)
            {
                //console.log(tableData);
                if(tableData[i].number)
                {
                    number += parseInt(tableData[i].number);
                    var goods_weight = tableData[i].weight ? parseFloat(tableData[i].weight).toFixed(3) : 0;
                    weight += goods_weight * parseInt(tableData[i].number);
                    selling_price += parseFloat(tableData[i].selling_price).toFixed(3) * parseInt(tableData[i].number) ;
                    purchase_price += parseFloat(tableData[i].purchase_price).toFixed(3) * parseInt(tableData[i].number);
                    freight_category_id = tableData[i].freight_category_id;
                    //var goods_freight = get_freight(tableData[i].freight_category_id, goods_weight * parseInt(tableData[i].number));
                    // freight += goods_freight;
                    /*
                    if(freight_arr[tableData[i].freight_category_id])
                    {

                        freight_arr[tableData[i].freight_category_id] = freight_arr[tableData[i].freight_category_id]+ goods_weight * parseInt(tableData[i].number);
                    }
                    else{
                        freight_arr[tableData[i].freight_category_id] = goods_weight * parseInt(tableData[i].number);
                    }
                    */
                    /*
                    if(tableData[i].freight_category_id)
                    {
                        if(last_freight_category_id)
                        {
                            if(last_freight_category_id > tableData[i].freight_category_id)
                            {
                                freight_category_id = last_freight_category_id;
                            }else{
                                freight_category_id = tableData[i].freight_category_id;
                                last_freight_category_id = tableData[i].freight_category_id;
                            }
                        }else{
                            last_freight_category_id = tableData[i].freight_category_id;
                            freight_category_id = tableData[i].freight_category_id;
                        }
                    }
                    */
                }
            }
            //问题：
            /*
            $.each(freight_arr, function(i, val) {
                freight +=  get_freight(i, parseFloat(val).toFixed(3));
            })
            */
            weight = weight.toFixed(3);
            freight =  freight_category_id ? get_freight(freight_category_id,weight) : 0;
            //运费四舍五入
            //freight = Math.round(freight);

            paypal_fee = parseInt(((selling_price + freight) * parseFloat("{{ setting('paypal_fee') }}")) * 100)/100;
            total = parseFloat(selling_price) + parseFloat(freight) + parseFloat(paypal_fee);
            purchase_price = parseFloat(purchase_price).toFixed(3)
            $("#weight").text(weight);
            $("#selling_price").text(selling_price);
            $("#purchase_price").text(purchase_price);
            $("#freight").text(freight);
            $("#paypal_fee").text(paypal_fee);
            $("#total").text(total);
        }

        function get_freight(freight_category_id,goods_weight) {
            var freight = 0;
            if(goods_weight <=0 || !freight_category_id)
            {
                return 0;
            }
           //console.log(goods_weight);
            var first_freight =  parseInt(freight_config[freight_category_id][freight_area_code]['first_freight']);
            var continued_freight =  parseInt(freight_config[freight_category_id][freight_area_code]['continued_freight']);
            if(goods_weight <= 0.5)
            {
                return first_freight;
            }
            var continued_weight = goods_weight - 0.5;
            var continued_weight_count = Math.ceil(continued_weight/0.5);
            continued_freight = continued_freight * continued_weight_count;
            return first_freight + continued_freight;
        }

        function appendTbody(node) {
            var cart_data = [];
            var ajax_data = node;
            var tableData = layui.table.cache.cart;
            if(tableData != null)
            {
                for (var i=0;i<tableData.length;i++)
                {
                    cart_data.push(tableData[i]);
                }
            }
            if(typeof(order_id)!="undefined" && order_id.length>0)
            {
                ajax_data['_token'] = "{!! csrf_token() !!}";
                ajax_data['order_id'] = node['order_id'] = order_id;
                var load = layer.load();
                $.ajax({
                    url : "{{ guard_url('order_goods') }}",
                    data : ajax_data,
                    type : 'POST',
                    success : function (data) {
                        layer.close(load);
                        if(data.code == 0) {
                            node = data.data;
                            node.number = 1;
                            cart_data.push(node);
                            table.reload("cart",{data:cart_data});
                            handle_number();
                        }else{
                            layer.msg(data.msg);
                        }
                    },
                    error : function (jqXHR, textStatus, errorThrown) {
                        layer.close(load);
                        $.ajax_error(jqXHR, textStatus, errorThrown);
                    }
                });
            }else{
                node.number = 1;
                cart_data.push(node);
                table.reload("cart",{data:cart_data});
                handle_number();
            }
        }
        $("body").on('click','#size a',function () {
            var i = $(this).attr('i');
            var node = sizes[i];
            $.onNodeClick(node);
        })

        window.handle_number = handle_number;
        window.get_freight = get_freight;
        window.appendTbody = handle_number;

        form.on('select(customer)', function(data){
            var customer_id = data.value;
            if(!customer_id)
            {
                return false;
            }
            var ajax_data = {'_token':"{!! csrf_token() !!}",id:customer_id};
            var load = layer.load();
            $.ajax({
                url : "{{ guard_url('get_customer') }}",
                data : ajax_data,
                type : 'get',
                success : function (data) {
                    layer.close(load);
                    if(data.code == 0) {
                        $("#address").text(data.data.address ? data.data.address : '');
                        freight_area_code = data.data.area_code;
                        handle_number();
                    }else{
                        layer.msg(data.msg);
                    }
                },
                error : function (jqXHR, textStatus, errorThrown) {
                    layer.close(load);
                    $.ajax_error(jqXHR, textStatus, errorThrown);
                }
            });
        });


    });

</script>
