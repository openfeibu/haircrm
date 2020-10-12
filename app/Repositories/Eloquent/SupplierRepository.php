<?php

namespace App\Repositories\Eloquent;

use App\Models\Goods;
use App\Repositories\Eloquent\SupplierRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class SupplierRepository extends BaseRepository implements SupplierRepositoryInterface
{
    public function model()
    {
        return config('model.supplier.supplier.model');
    }
    public function suppliers()
    {
        return $this->orderBy('id','asc')->get();
    }
    public function getSupplier($goods_id)
    {
        $category_id = Goods::where('id',$goods_id)->value('category_id');
        $supplier_id = app(CategoryRepository::class)->getSupplierId($category_id);
        if(!$supplier_id)
        {
            return [
                'id' => 0,
                'name' => '',
                'code' => '',
            ];
        }
        return $this->find($supplier_id,['id','name','code'])->toArray();
    }
}