<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Goods;
use App\Models\GoodsAttributeValue;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\GoodsRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Log,Mail;

class HomeController extends BaseController
{
    public function __construct(
        CategoryRepository $categoryRepository,
        GoodsRepository $goodsRepository
    )
    {
        parent::__construct();
        $this->categoryRepository = $categoryRepository;
        $this->goodsRepository = $goodsRepository;
    }
    public function test()
    {
        $email = '1270864834@qq.com';
        $send = Mail::send('email', ['email' => $email,'name' => '吴志杰'], function($message) use($email) {
            $message->from(config('mail.from')['address'],config('mail.from')['name']);
            $message->subject('['.config('app.name').'] 邀请好友');
            $message->to($email);
        });
        var_dump($send);exit;
    }
    public function addGoods()
    {
        $old_category_id = 150;

        $category_ids = [
            '151' => 0,
            '152' => 10,
        ];
        foreach ($category_ids as $category_id => $add_purchase_price)
        {
            $this->addGoodsHandle($old_category_id,$category_id,$add_purchase_price);
        }
        echo "success";exit;
    }

    public function addGoodsHandle($old_category_id,$category_id,$add_purchase_price=0,$add_selling_price=0)
    {
        $new_goods = Goods::where('category_id',$category_id)->first();
        if($new_goods)
        {
            echo "已有该产品".$category_id;exit;
        }

        $old_goods = Goods::where('category_id',$old_category_id)->first()->toArray();
        $old_goods_attribute_values = GoodsAttributeValue::leftJoin('attribute_values','attribute_values.id','=','goods_attribute_value.attribute_value_id')
            ->where('goods_attribute_value.goods_id',$old_goods['id'])
            ->orderBy('attribute_values.order','asc')
            ->orderBy('attribute_values.id','asc')
            ->get(['goods_attribute_value.*','attribute_values.value'])
            ->toArray();

        $category = Category::where('id',$category_id)->first();
        $category_ids = $category->category_ids ? $category_id .','.$category->category_ids : $category_id;
        $category_id_arr = explode(',',$category_ids);
        $categories_name_arr = Category::whereIn('id',$category_id_arr)->orderBy('id','asc')->pluck('name')->toArray();
        $categories_names = implode(" ",$categories_name_arr);

        $new_goods = $this->goodsRepository->create([
            'category_id' => $category_id,
            'category_ids' => $category_ids,
            'name' => $categories_names,
            'attribute_id' => $old_goods['attribute_id'],
            'purchase_price' => $old_goods['purchase_price'] ? $old_goods['purchase_price'] + $add_purchase_price : 0,
            'selling_price' => $old_goods['selling_price'] ? $old_goods['selling_price'] + $add_selling_price : 0,
        ]);

        $goods_attribute_values = [];
        foreach ($old_goods_attribute_values as $key => $goods_attribute_value)
        {
            $goods_attribute_values[] = [
                'attribute_value_id' => $goods_attribute_value['attribute_value_id'],
                'purchase_price' => $goods_attribute_value['purchase_price'] + $add_purchase_price,
                'selling_price' => $goods_attribute_value['selling_price'] + $add_selling_price,
                'goods_id' => $new_goods->id
            ];
        }
        GoodsAttributeValue::insert($goods_attribute_values);
    }
}
