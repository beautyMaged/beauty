<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Review;
use Illuminate\Http\Request;
use App\Model\Product;

class ReviewsController extends Controller
{
    public function list(Request $request) {
//search
        $sellerId = auth('seller')->id();

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search'))
        {
            $key = explode(' ', $request['search']);
            $product_id = Product::where('added_by','seller')->where('user_id',$sellerId)->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->where('name', 'like', "%{$value}%");
                    }
                })->pluck('id')->toArray();
            $reviews = Review::with(['product'])
                    ->whereIn('product_id',$product_id);
            $query_param = ['search' => $request['search']];
        }else {
            $reviews = Review::with(['product'])->whereHas('product', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId)->where('added_by', 'seller');
        });
        }
        //dd($reviews->count());
        $reviews = $reviews->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('seller-views.reviews.list', compact('reviews','search'));

    }
}
