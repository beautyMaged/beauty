<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use App\Model\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function get_banners(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request['banner_type'] == 'all') {
            $banners = Banner::where(['published' => 1])->get();
        } elseif ($request['banner_type'] == 'main_banner') {
            $banners = Banner::where(['published' => 1, 'banner_type' => 'Main Banner'])->get();
        } elseif ($request['banner_type'] == 'main_section_banner') {
            $banners = Banner::where(['published' => 1, 'banner_type' => 'Main Section Banner'])->get();
        }else {
            $banners = Banner::where(['published' => 1, 'banner_type' => 'Footer Banner'])->get();
        }
        $pro_ids = [];
        $data = [];
        foreach ($banners as $banner) {
            if ($banner['resource_type'] == 'product' && !in_array($banner['resource_id'], $pro_ids)) {
                array_push($pro_ids,$banner['resource_id']);
                $product = Product::find($banner['resource_id']);
                $banner['product'] = Helpers::product_data_formatting($product);
            }
            $data[] = $banner;
        }

        return response()->json($data, 200);

    }
}
