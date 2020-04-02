<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\OrderRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{

    public function boot()
    {
        $this->fieldSearchable = config('model.order.order.search');
    }
    public function model()
    {
        return config('model.order.order.model');
    }
    public function operation($order)
    {
        /* 取得订单状态、发货状态、付款状态 */
        $os = $order['order_status'];
        $ss = $order['shipping_status'];
        $ps = $order['pay_status'];

        /* 根据状态返回可执行操作 */
        $list = array();
        if ($os == 'unconfirmed')
        {
            /* 状态：未确认 => 未付款、未发货 */
            $list['confirm']    = true; // 确认
            $list['cancel']     = true; // 取消
            $list['pay'] = true;  // 付款
        }
        elseif ($os == 'confirmed')
        {
            /* 状态：已确认 */
            if ($ps == 'unpaid')
            {
                /* 状态：已确认、未付款 */
                if ($ss == 'unshipped')
                {
                    $list['cancel'] = true; // 取消
                    $list['pay'] = true; // 付款
                }
                else
                {
                    if ($ss == 'shipped')
                    {
                        $list['receive'] = true; // 收货确认
                    }
                    $list['pay'] = true; // 付款
                    //$list['unship'] = true; // 设为未发货
                    $list['return'] = true; // 退货
                }
            }
            elseif($ps == 'paid')
            {
                /* 状态：已确认、已付款 */
                if ($ss == 'unshipped')
                {
                    // $list['unpay'] = true; // 设为未付款
                    $list['to_delivery'] = true; // 去发货
                    $list['cancel'] = true; // 取消
                }
                else
                {
                    if ($ss == 'shipped')
                    {
                        $list['receive'] = true; // 收货确认
                    }
                    //$list['unship'] = true; // 设为未发货
                    $list['return'] = true; // 退货（包括退款）
                }
            }
        }
        elseif ($os == 'cancelled')
        {
            // $list['confirm'] = true;
            $list['remove'] = true;
        }
        elseif ($os == 'returned')
        {
            //$list['confirm'] = true;
        }
        return $list;
    }
}