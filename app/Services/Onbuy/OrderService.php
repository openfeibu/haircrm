<?php
namespace App\Services\Onbuy;

use App\Models\Onbuy\OrderProduct as OnbuyOrderProductModel;
use App\Models\Schedule;
use GuzzleHttp\Client;
use App\Exceptions\OutputServerMessageException;
use Log, DB;
use App\Models\Onbuy\ProductBid;
use App\Models\Onbuy\ProductBidTask;
use Xigen\Library\OnBuy\Product\Product;
use Xigen\Library\OnBuy\Product\Listing;
use Xigen\Library\OnBuy\Order\Order;
use App\Models\Onbuy\Order as OnbuyOrderModel;

class OrderService
{
    public $seller_id;

    public function __construct($seller_id)
    {
        $this->seller_id = $seller_id;

    }
    public function syncHandle($offset=0,$limit=50)
    {
        $onbuy_token = getOnbuyToken($this->seller_id);
        $orders = new Order($onbuy_token);
        $orders->getOrder(
            ['status' => 'all'],
            ['created' => 'desc'],
            $limit,
            $offset
        );
        $orders = $orders->getResponse();
        if(count($orders['results']) <=0 )
        {
            return true;
        }
        $data = [];
        $order_product_data = [] ;
        $picked = false;
        foreach ($orders['results'] as $key => $order)
        {
            $is_exist = OnbuyOrderModel::where('seller_id',$this->seller_id)->where('order_id',$order['order_id'])->value('id');
            if($is_exist)
            {
                $picked = true;
                break;
            }
            if(!$is_exist)
            {

                $data[$key] = [
                    'order_id' => $order['order_id'],
                    'onbuy_internal_reference' => $order['onbuy_internal_reference'],
                    'date' => $order['date'],
                    'updated_at' => $order['updated_at'],
                    'cancelled_at' => $order['cancelled_at'],
                    'shipped_at' => $order['shipped_at'],
                    'status' => $order['status'],
                    'site_id' => $order['site_id'],
                    'site_name' => $order['site_name'],
                    'price_subtotal' => $order['price_subtotal'],
                    'price_delivery' => $order['price_delivery'],
                    'price_total' => $order['price_total'],
                    'price_discount' => $order['price_discount'],
                    'sales_fee_ex_VAT' => $order['sales_fee_ex_VAT'],
                    'sales_fee_inc_VAT' => $order['sales_fee_inc_VAT'],
                    'currency_code' => $order['currency_code'],
                    'dispatched' => $order['dispatched'],
                    'delivery_service' => $order['delivery_service'],
                    'stripe_transaction_id' => $order['stripe_transaction_id'],
                    'paypal_capture_id' => $order['paypal_capture_id'],
                    'buyer_name' => $order['buyer']['name'],
                    'buyer_email' => $order['buyer']['email'],
                    'buyer_phone' => $order['buyer']['phone'],
                    'buyer_ip_address' => $order['buyer']['ip_address'],
                    'billing_address' => json_encode($order['billing_address']),
                    'delivery_address' => json_encode($order['delivery_address']),
                    'fee_boost_marketing_fee_excluding_vat' => $order['fee']['boost_marketing_fee_excluding_vat'],
                    'fee_category_fee_excluding_vat' => $order['fee']['category_fee_excluding_vat'],
                    'fee_delivery_fee_excluding_vat' => $order['fee']['delivery_fee_excluding_vat'],
                    'fee_total_fee_excluding_vat' => $order['fee']['total_fee_excluding_vat'],
                    'fee_vat_rate' => $order['fee']['vat_rate'],
                    'fee_total_fee_including_vat' => $order['fee']['total_fee_including_vat'],
                    'tax_total' => $order['tax']['tax_total'],
                    'tax_subtotal' => $order['tax']['tax_subtotal'],
                    'tax_delivery' => $order['tax']['tax_delivery'],
                    'delivery_tag' => $order['delivery_tag'],
                    'tracking_number' => $order['tracking']['tracking_number'] ?? '',
                    'tracking_supplier_name' => $order['tracking']['supplier_name'] ?? '',
                    'tracking_url' => $order['tracking']['tracking_url'] ?? '',
	                'is_refund' => isset($order['refunds']) && $order['refunds'] ? 1 : 0,
                    'seller_id' => $this->seller_id,
                ];

                foreach($order['products'] as $product)
                {
                    $order_product_data[] = [
                        'order_id' => $order['order_id'],
                        'onbuy_internal_reference' => $product['onbuy_internal_reference'],
                        'name' => $product['name'],
                        'sku' => $product['sku'],
                        'seller_delivery_template_id' => $product['seller_delivery_template_id'],
                        'price_delivery_total' => $product['price_delivery_total'],
                        'condition' => $product['condition'],
                        'condition_id' => $product['condition_id'],
                        'quantity' => $product['quantity'],
                        'quantity_dispatched' => $product['quantity_dispatched'],
                        'unit_price' => $product['unit_price'],
                        'total_price' => $product['total_price'],
                        'expected_dispatch_date' => $product['expected_dispatch_date'],
                        'expected_delivery_date' => $product['expected_delivery_date'],
                        'file_location_prefix' => $product['file_location_prefix'],
                        'opc' => $product['opc'],
                        'image_urls' => json_encode($product['image_urls']),
                        'tax_delivery' => $product['tax']['tax_delivery'],
                        'tax_product' => $product['tax']['tax_product'],
                        'tax_total' => $product['tax']['tax_total'],
                        'commission_fee' => $product['fee']['commission_fee'],
                        'commission_fee_including_tax' => $product['fee']['commission_fee_including_tax'],
                        'tracking_number' => $product['tracking']['tracking_number'] ?? '',
                        'tracking_supplier_name' => $product['tracking']['supplier_name'] ?? '',
                        'tracking_url' => $product['tracking']['tracking_url'] ?? '',
                        'seller_id' => $this->seller_id,
                    ];
                }
            }
        }
        //???????????????
        if(count($data))
        {
            DB::table("onbuy_orders")->insert($data);
            if(count($order_product_data))
            {
                DB::table("onbuy_order_products")->insert($order_product_data);
            }

        }
        //?????????????????????
        if(!$picked){
            $this->syncHandle($offset+$limit);
        }
        return true;
    }
    public function automaticSyncUpdate($offset=0,$limit=50)
    {
        $order_ids = OnbuyOrderModel::where('status','Awaiting Dispatch')
            ->where('seller_id',$this->seller_id)
            ->offset($offset)
            ->limit($limit)
            ->pluck('order_id')
            ->toArray();

        if(count($order_ids))
        {
            $onbuy_token = getOnbuyToken($this->seller_id);
            $orders = new Order($onbuy_token);
            $order_ids = implode(',',$order_ids);
            $orders->getOrder(
                [
                    'status' => 'all',
                    'order_ids' => $order_ids,
                ],
                [
                    'created' => 'desc'
                ]
            );
            $orders = $orders->getResponse();
            if(count($orders['results']) !=0 )
            {
                $this->syncUpdate($orders['results']);
            }
            $this->syncHandle($offset+$limit);
        }

        return  true;


    }
    public function syncUpdate($orders)
    {
    	
        foreach ($orders as $key => $order)
        {
            $data = [
                'onbuy_internal_reference' => $order['onbuy_internal_reference'],
                'updated_at' => $order['updated_at'],
                'cancelled_at' => $order['cancelled_at'],
                'shipped_at' => $order['shipped_at'],
                'status' => $order['status'],
                'dispatched' => $order['dispatched'],
                'delivery_tag' => $order['delivery_tag'],
                'tracking_number' => $order['tracking']['tracking_number'] ?? '',
                'tracking_supplier_name' => $order['tracking']['supplier_name'] ?? '',
                'tracking_url' => $order['tracking']['tracking_url'] ?? '',
	            'is_refund' => isset($order['refunds']) && $order['refunds'] ? 1 : 0,
                //'date' => $order['date'],
                //'site_id' => $order['site_id'],
                //'site_name' => $order['site_name'],
//                'price_subtotal' => $order['price_subtotal'],
//                'price_delivery' => $order['price_delivery'],
//                'price_total' => $order['price_total'],
//                'price_discount' => $order['price_discount'],
//                'sales_fee_ex_VAT' => $order['sales_fee_ex_VAT'],
//                'sales_fee_inc_VAT' => $order['sales_fee_inc_VAT'],
//                'currency_code' => $order['currency_code'],
//                'delivery_service' => $order['delivery_service'],
//                'stripe_transaction_id' => $order['stripe_transaction_id'],
//                'paypal_capture_id' => $order['paypal_capture_id'],
//                'buyer_name' => $order['buyer']['name'],
//                'buyer_email' => $order['buyer']['email'],
//                'buyer_phone' => $order['buyer']['phone'],
//                'buyer_ip_address' => $order['buyer']['ip_address'],
//                'billing_address' => json_encode($order['billing_address']),
//                'delivery_address' => json_encode($order['delivery_address']),
//                'fee_boost_marketing_fee_excluding_vat' => $order['fee']['boost_marketing_fee_excluding_vat'],
//                'fee_category_fee_excluding_vat' => $order['fee']['category_fee_excluding_vat'],
//                'fee_delivery_fee_excluding_vat' => $order['fee']['delivery_fee_excluding_vat'],
//                'fee_total_fee_excluding_vat' => $order['fee']['total_fee_excluding_vat'],
//                'fee_vat_rate' => $order['fee']['vat_rate'],
//                'fee_total_fee_including_vat' => $order['fee']['total_fee_including_vat'],
//                'tax_total' => $order['tax']['tax_total'],
//                'tax_subtotal' => $order['tax']['tax_subtotal'],
//                'tax_delivery' => $order['tax']['tax_delivery'],

            ];

            foreach($order['products'] as $product)
            {
                $order_product_data = [
                    'quantity_dispatched' => $product['quantity_dispatched'],
                    'tracking_number' => $product['tracking']['tracking_number'] ?? '',
                    'tracking_supplier_name' => $product['tracking']['supplier_name'] ?? '',
                    'tracking_url' => $product['tracking']['tracking_url'] ?? '',
                ];
                OnbuyOrderProductModel::where('seller_id',$this->seller_id)->where('onbuy_internal_reference',$product['onbuy_internal_reference'])->update($order_product_data);
	            if(!$data['tracking_number'])
	            {
		            $data['tracking_number'] = $order_product_data['tracking_number'];
		            $data['tracking_supplier_name'] = $order_product_data['tracking_supplier_name'];
		            $data['tracking_url'] = $order_product_data['tracking_url'];
	            }
            }
	        OnbuyOrderModel::where('seller_id',$this->seller_id)->where('order_id',$order['order_id'])->update($data);
        }
        return true;
    }


}