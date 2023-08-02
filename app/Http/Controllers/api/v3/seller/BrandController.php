<?php

namespace App\Http\Controllers\api\v3\seller;

use App\Http\Controllers\Controller;
use App\Model\Brand;

class BrandController extends Controller
{
    public function getBrands()
    {
        try {
            $brands = Brand::all();
        } catch (\Exception $e) {
        }

        return response()->json($brands,200);
    }
}
