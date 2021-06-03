<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\ResourceController as BaseController;
use Illuminate\Http\Request;
use Excel;

class PriceCalculationOfPlasticBagResourceController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(Request $request)
    {
        return $this->response->title('塑料袋价格计算')
            ->view('price_calculation_of_plastic_bag.index')
            ->output();
    }
    public function getPrice(Request $request)
    {
        try {
            $measuring_unit = $request->measuring_unit;
            switch ($measuring_unit)
            {
                case "cm":
                    $calculation_kg = $request->thickness * $request->width * $request->length / 1000000 * $request->proportion ;
                    $calculation_price_rmb = $request->thickness * $request->width * $request->length / 1000000 * $request->proportion * $request->factory_price;
                    break;
                case "inch":
                    $calculation_kg = $request->thickness * $request->width * $request->length / 100000 / 2.2046 * $request->proportion ;
                    $calculation_price_rmb = $request->thickness * $request->width * $request->length / 100000 / 2.2046 * $request->proportion * $request->factory_price;
                    break;
            }

            $calculation_kg =  ceil($calculation_kg * 1000000 / 100) / 10000 ; //向上取
            $calculation_price_rmb =  ceil($calculation_price_rmb * 100000 / 100) / 1000 ; //向上取
            return $this->response
                ->data([
                    'calculation_kg' => $calculation_kg,
                    'calculation_price_rmb' => $calculation_price_rmb,
                ])
                ->status("success")
                ->json();

        } catch (Exception $e) {

            return $this->response->message($e->getMessage())
                ->status("error")
                ->code(400)
                ->url(guard_url('price_calculation_of_plastic_bag'))
                ->redirect();
        }
    }
}
