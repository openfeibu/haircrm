<?php

namespace App\Http\Controllers\Admin\Onbuy;

use App\Http\Controllers\Admin\Onbuy\BaseController;
use App\Models\Onbuy\Product as OnbuyProductModel;
use App\Models\Onbuy\ProductBid as OnbuyProductBidModel;
use App\Models\Onbuy\ProductBidTask as OnbuyProductBidTaskModel;
use Illuminate\Http\Request;
use DB;

class ProductBidResourceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        if ($this->response->typeIs('json')) {
            $search = $request->get('search',[]);
            $bids = OnbuyProductBidModel::when($search ,function ($query) use ($search){

            });
            $bids = $bids->orderBy('created_at','desc')->paginate($request->get('limit',50));


            return $this->response
                ->success()
                ->count($bids->total())
                ->data($bids->toArray()['data'])
                ->output();

        }

        return $this->response->title(trans('goods.name'))
            ->view('onbuy.listing.index')
            ->data(['limit' => $request->get('limit',50)])
            ->output();
    }
    public function update(Request $request, OnbuyProductBidModel $product_bid)
    {
        try {
            $attributes = $request->all();
            $product_bid->update($attributes);
            return $this->response->message(trans('messages.success.updated'))
                ->code(0)
                ->status('success')
                ->url(guard_url('onbuy/product_bid'))
                ->redirect();
        } catch (Exception $e) {
            return $this->response->message($e->getMessage())
                ->code(400)
                ->status('error')
                ->url(guard_url('onbuy/product_bid/'))
                ->redirect();
        }

    }

}
