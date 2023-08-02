<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Model\OrderDetail;

class InhouseProductSaleController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where(['parent_id' => 0])->get();
        $query_param = ['category_id' => $request['category_id']];

        $products = Product::where(['added_by' => 'admin'])
            ->when($request->has('category_id') && $request['category_id'] != 'all', function ($query) use ($request) {
                $query->whereJsonContains('category_ids', [[['id' => (string)$request['category_id']]]]);
            })->with(['order_details'])->paginate(Helpers::pagination_limit())->appends($query_param);
        $category_id = $request['category_id'];

        return view('admin-views.report.inhouse-product-sale', compact('categories', 'category_id', 'products'));
    }
}
