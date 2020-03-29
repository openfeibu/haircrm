<?php

namespace App\Http\Controllers;


use App\Repositories\Eloquent\AttributeRepository;
use App\Repositories\Eloquent\AttributeValueRepository;
use App\Repositories\Eloquent\CategoryRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Log;

class AttributeController extends BaseController
{
    public function __construct(
        AttributeRepository $attributeRepository,
        AttributeValueRepository $attributeValueRepository
    )
    {
        parent::__construct();
        $this->repository = $attributeRepository;
        $this->attributeValueRepository = $attributeValueRepository;
    }
    public function getContent(Request $request)
    {
        $attribute_id = $request->id;
        $attribute_values = $this->attributeValueRepository->getAttributeValues($attribute_id);

    }

}
