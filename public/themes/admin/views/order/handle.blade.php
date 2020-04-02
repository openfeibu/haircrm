@{{# if(typeof(d.operation.confirm) != "undefined" && d.operation.confirm) { }}
<a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="confirm">@{{ order_lang['operation']['confirm'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.pay) != "undefined" && d.operation.pay) { }}
<a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="pay">@{{ order_lang['operation']['pay'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.unpay) != "undefined" && d.operation.unpay) { }}
<a class="layui-btn layui-btn-warm layui-btn-sm order-btn" lay-event="unpay">@{{ order_lang['operation']['unpay'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.unship) != "undefined" && d.operation.unship) { }}
<a class="layui-btn layui-btn-warm layui-btn-sm order-btn" lay-event="unship">@{{ order_lang['operation']['unship'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.to_delivery) != "undefined" && d.operation.to_delivery) { }}
<a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="to_delivery">@{{ order_lang['operation']['to_delivery'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.receive) != "undefined" && d.operation.receive) { }}
<a class="layui-btn layui-btn-normal layui-btn-sm order-btn" lay-event="receive">@{{ order_lang['operation']['receive'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.cancel) != "undefined" && d.operation.cancel) { }}
<a class="layui-btn layui-btn-danger layui-btn-sm order-btn" lay-event="cancel">@{{ order_lang['operation']['cancel'] }}</a>
@{{#  } }}
@{{# if(typeof(d.operation.return) != "undefined" && d.operation.return) { }}
<a class="layui-btn layui-btn-danger layui-btn-sm order-btn" lay-event="return_order">@{{ order_lang['operation']['return'] }}</a>
@{{#  } }}
