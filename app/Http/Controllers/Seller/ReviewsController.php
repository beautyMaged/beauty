<?php

namespace App\Http\Controllers\Seller;

use App\User;
use App\CPU\Helpers;
use App\Model\Review;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;
use App\CPU\ProductManager;

class ReviewsController extends Controller
{
    public function list(Request $request)
    {
        //search
        $sellerId = auth('seller')->id();
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $product_id = Product::where('added_by', 'seller')->where('user_id', $sellerId)->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->where('name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();

            $customer_id = User::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('f_name', 'like', "%{$value}%")
                        ->orWhere('l_name', 'like', "%{$value}%");
                }
            })->pluck('id')->toArray();

            $reviews = Review::whereHas('product', function($query) use($sellerId){
                    $query->where('added_by', 'seller')->where('user_id', $sellerId);
                })
                ->with(['product'])
                ->where(function($q) use($product_id, $customer_id){
                    $q->whereIn('product_id', $product_id)->orWhereIn('customer_id', $customer_id);
                });

            $query_param = ['search' => $request['search']];
        } else
        {
            $reviews = Review::with(['product'])->whereHas('product', function ($query) use ($sellerId) {
                $query->where('user_id', $sellerId)->where('added_by', 'seller');
            })
                ->when($request->product_id != null, function ($query) {
                    $query->where('product_id', request('product_id'));
                })
                ->when($request->customer_id != null, function ($query) {
                    $query->where('customer_id', request('customer_id'));
                })
                ->when($request->status != null, function ($query) {
                    $query->where('status', request('status'));
                })
                ->when($request->from && $request->to, function ($query) use ($request) {
                    $query->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
                });
        }

        $reviews = $reviews->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        $products = Product::whereNotIn('request_status',[0])->select('id', 'name')->where('user_id', $sellerId)->get();
        $customers = User::whereNotIn('id',[0])->select('id', 'name', 'f_name', 'l_name')->get();
        $customer_id = $request['customer_id'];
        $product_id = $request['product_id'];
        $status = $request['status'];
        $from = $request->from;
        $to = $request->to;

        return view('seller-views.reviews.list', compact('reviews', 'search', 'products', 'customers', 'customer_id', 'product_id', 'status', 'from', 'to'));
    }

    public function export(Request $request)
    {
        $sellerId = auth('seller')->id();
        $product_id = $request['product_id'];
        $customer_id = $request['customer_id'];
        $status = $request['status'];
        $from = $request['from'];
        $to = $request['to'];
        $data = Review::with(['customer', 'product'])->whereHas('product', function ($query) use ($sellerId) {
            $query->where('user_id', $sellerId)->where('added_by', 'seller');
        })
            ->when($product_id != null, function ($q) use ($request) {
                $q->where('product_id', $request['product_id']);
            })
            ->when($customer_id != null, function ($q) use ($request) {
                $q->where('customer_id', $request['customer_id']);
            })
            ->when($status != null, function ($q) use ($request) {
                $q->where('status', $request['status']);
            })
            ->when($to != null && $from != null, function ($query) use ($from, $to) {
                $query->whereBetween('created_at', [$from . ' 00:00:00', $to . ' 23:59:59']);
            })
            ->get();
        if ($data->count() == 0) {
            Toastr::warning('No data found for export!');
            return back();
        }
        return (new FastExcel(ProductManager::export_product_reviews($data)))->download('Review' . date('d_M_Y') . '.xlsx');
    }
    public function status(Request $request)
    {
        $review = Review::find($request->id);
        $review->status = $request->status;
        $review->save();
        Toastr::success('Review status updated!');
        return back();
    }
}
