<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\CPU\Helpers;
use App\Model\Review;
use App\Model\Product;
use App\CPU\ProductManager;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Rap2hpoutre\FastExcel\FastExcel;

class ReviewsController extends Controller
{
    function list(Request $request)
    {
        $query_param = [];
        if (!empty($request->from) && empty($request->to)) {
            Toastr::warning('Please select to date!');
        }
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $product_id = Product::where(function ($q) use ($key) {
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
            $reviews = Review::WhereIn('product_id',  $product_id)->orWhereIn('customer_id', $customer_id);
            $query_param = ['search' => $request['search']];
        } else {
            $reviews = Review::with(['product', 'customer'])
                ->when($request->product_id != null, function ($q) {
                    $q->where('product_id', request('product_id'));
                })->when($request->customer_id != null, function ($q) {
                    $q->where('customer_id', request('customer_id'));
                })->when($request->status != null, function ($q) {
                    $q->where('status', request('status'));
                })->when($request->from && $request->to, function ($q) use ($request) {
                    $q->whereBetween('created_at', [$request->from . ' 00:00:00', $request->to . ' 23:59:59']);
                });
        }
        $reviews = $reviews->latest()->paginate(Helpers::pagination_limit());
        $products = Product::whereNotIn('request_status',[0])->select('id', 'name')->get();
        $customers = User::whereNotIn('id',[0])->select('id', 'name', 'f_name', 'l_name')->get();
        $customer_id = $request['customer_id'];
        $product_id = $request['product_id'];
        $status = $request['status'];
        $from = $request->from;
        $to = $request->to;

        return view('admin-views.reviews.list', compact('reviews', 'search', 'products', 'customers', 'from', 'to', 'customer_id', 'product_id', 'status'));
    }
    public function export(Request $request)
    {

        $product_id = $request['product_id'];
        $customer_id = $request['customer_id'];
        $status = $request['status'];
        $from = $request['from'];
        $to = $request['to'];



        $data = Review::with(['product', 'customer'])
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



        if($data->count()==0){

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
