<?php

namespace App\Http\Controllers\Seller;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\OrderDetail;
use App\Model\Product;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductReportController extends Controller
{

    public function all_product(Request $request){
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $seller_id = auth('seller')->id();
        $query_param = ['search' => $search, 'date_type' => $date_type, 'from' => $from, 'to' => $to];

        $chart_data = self::all_product_chart_filter($request);

        $product_query = Product::with(['reviews'])
            ->with(['order_details' => function ($query) {
                $query->select(
                    DB::raw("product_id, SUM(price*qty) as total_sold_amount, sum(qty) as product_quantity")
                )->where('delivery_status', 'delivered')->groupBy('product_id');
            }])
            ->when($search, function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            })
            ->where(['user_id' => $seller_id, 'added_by' => 'seller']);
        $products = self::create_date_wise_common_filter($product_query, $date_type, $from, $to)
            ->latest('created_at')
            ->paginate(Helpers::pagination_limit())
            ->appends($query_param);

        $total_sales_value = 0;
        foreach($products as $key=>$product){
            $total_sales_value += (isset($product->order_details[0]->total_sold_amount) ? $product->order_details[0]->total_sold_amount : 0) / (isset($product->order_details[0]->product_quantity) ? $product->order_details[0]->product_quantity : 1);
        }

        $total_product_sale_query = Product::with(['order_details'=>function($query){
                $query->select(
                    DB::raw("product_id, sum(qty*price) as total_sale_amount, sum(qty) as product_quantity, sum(discount) as total_discount")
                );
            }])
            ->whereHas('order_details',function($query){
                $query->where('delivery_status', 'delivered');
            })
            ->where(['user_id' => $seller_id, 'added_by' => 'seller']);
        $total_product_sales = self::create_date_wise_common_filter($total_product_sale_query, $date_type, $from, $to)
            ->latest('created_at')
            ->get();

        $total_product_sale = 0;
        $total_product_sale_amount = 0;
        $total_discount_given = 0;
        if(count($total_product_sales) > 0) {
            foreach ($total_product_sales as $sales) {
                foreach ($sales->order_details as $sale) {
                    $total_product_sale += isset($sale->product_quantity) ? $sale->product_quantity : 0;
                    $total_discount_given += isset($sale->total_discount) ? $sale->total_discount : 0;
                    $total_product_sale_amount += isset($sale->total_sale_amount) ? $sale->total_sale_amount : 0;
                }
            }
        }

        $reject_product_count_query = Product::where(['request_status' => 2, 'user_id' => $seller_id, 'added_by' => 'seller']);
        $reject_product_count = self::create_date_wise_common_filter($reject_product_count_query, $date_type, $from, $to)->count();

        $pending_product_count_query = Product::where(['request_status'=>'0', 'user_id' => $seller_id, 'added_by' => 'seller']);
        $pending_product_count = self::create_date_wise_common_filter($pending_product_count_query, $date_type, $from, $to)->count();

        $active_product_count_query = Product::where(['request_status' => 1, 'user_id' => $seller_id, 'added_by' => 'seller']);
        $active_product_count = self::create_date_wise_common_filter($active_product_count_query, $date_type, $from, $to)->count();

        $product_count = array(
            'reject_product_count'=> $reject_product_count,
            'active_product_count'=> $active_product_count,
            'pending_product_count'=> $pending_product_count
        );

        $top_product = OrderDetail::with('product')
            ->select(DB::raw("product_id, sum(qty*price) as total_amount"))
            ->whereHas('product', function ($query) use($seller_id){
                $query->where(['user_id' => $seller_id, 'added_by' => 'seller', 'delivery_status'=>'delivered']);
            })
            ->where(['delivery_status'=>'delivered'])
            ->groupBy('product_id')
            ->orderBy("total_amount", 'desc')
            ->take(5)
            ->get();


        return view('seller-views.report.all-product', compact('products', 'chart_data', 'total_sales_value',
            'total_product_sale', 'total_product_sale_amount', 'top_product', 'total_discount_given', 'product_count', 'search', 'date_type', 'from', 'to'));
    }

    public function all_product_chart_filter($request)
    {
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        if ($date_type == 'this_year') { //this year table
            $number = 12;
            $default_inc = 1;
            $current_start_year = date('Y-01-01');
            $current_end_year = date('Y-12-31');
            $from_year = Carbon::parse($from)->format('Y');

            $this_year = self::all_product_same_year($current_start_year, $current_end_year, $from_year, $number, $default_inc);
            return $this_year;

        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            $this_month = self::all_product_same_month($current_month_start, $current_month_end, $month, $number, $inc);
            return $this_month;

        } elseif ($date_type == 'this_week') {
            $this_week = self::all_product_this_week();
            return $this_week;

        } elseif ($date_type == 'custom_date' && !empty($from) && !empty($to)) {
            $start_date = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date = Carbon::parse($to)->format('Y-m-d 23:59:59');
            $from_year = Carbon::parse($from)->format('Y');
            $from_month = Carbon::parse($from)->format('m');
            $from_day = Carbon::parse($from)->format('d');
            $to_year = Carbon::parse($to)->format('Y');
            $to_month = Carbon::parse($to)->format('m');
            $to_day = Carbon::parse($to)->format('d');

            if ($from_year != $to_year) {
                $different_year = self::all_product_different_year($start_date, $end_date, $from_year, $to_year);
                return $different_year;

            } elseif ($from_month != $to_month) {
                $same_year = self::all_product_same_year($start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            } elseif ($from_month == $to_month) {
                $same_month = self::all_product_same_month($start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function all_product_same_year($start_date, $end_date, $from_year, $number, $default_inc)
    {

        $products = self::all_product_date_common_query($start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(created_at) year, MONTH(created_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%M')"))
            ->latest('created_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $total_product[$month . '-' . $from_year] = 0;
            foreach ($products as $match) {
                if ($match['month'] == $inc) {
                    $total_product[$month . '-' . $from_year] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function all_product_same_month($start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));

        $products = self::all_product_date_common_query($start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(updated_at) year, MONTH(created_at) month, DAY(created_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%D')"))
            ->latest('created_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $total_product[$day . '-' . $month] = 0;
            foreach ($products as $match) {
                if ($match['day'] == $inc) {
                    $total_product[$day . '-' . $month] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function all_product_this_week()
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $products = self::all_product_date_common_query($start_date, $end_date)
            ->select(
                DB::raw('count(*) as total_product'),
                DB::raw("(DATE_FORMAT(created_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%D')"))
            ->latest('created_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $total_product[$day_name[$inc]] = 0;
            foreach ($products as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $total_product[$day_name[$inc]] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );
    }

    public function all_product_different_year($start_date, $end_date, $from_year, $to_year)
    {
        $products = self::all_product_date_common_query($start_date, $end_date)
            ->selectRaw('count(*) as total_product, YEAR(created_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y')"))
            ->latest('created_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $total_product[$inc] = 0;
            foreach ($products as $match) {
                if ($match['year'] == $inc) {
                    $total_product[$inc] = $match['total_product'];
                }
            }
        }

        return array(
            'total_product' => $total_product,
        );

    }

    public function all_product_date_common_query($start_date, $end_date)
    {
        $seller_id = auth('seller')->id();
        $query = Product::where(['user_id' => $seller_id, 'added_by' => 'seller'])
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date);

        return $query;
    }

    public function create_date_wise_common_filter($query, $date_type, $from, $to)
    {
        return $query->when(($date_type == 'this_year'), function ($query) {
            return $query->whereYear('created_at', date('Y'));
        })
            ->when(($date_type == 'this_month'), function ($query) {
                return $query->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'));
            })
            ->when(($date_type == 'this_week'), function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when(($date_type == 'custom_date' && !is_null($from) && !is_null($to)), function ($query) use ($from, $to) {
                return $query->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            });
    }

    public function all_product_export_excel(Request $request){
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $seller_id = auth('seller')->id();

        $product_query = Product::with(['reviews'])
            ->with(['order_details' => function ($query) {
                $query->select(
                    DB::raw("product_id, SUM(price*qty) as total_sold_amount, sum(qty) as product_quantity")
                )
                ->where('delivery_status', 'delivered')->groupBy('product_id');
            }])
            ->when($search, function ($query) use ($search) {
                $query->orWhere('name', 'like', "%{$search}%");
            })
            ->where(['user_id' => $seller_id, 'added_by' => 'seller']);

        $products = self::create_date_wise_common_filter($product_query, $date_type, $from, $to)->latest('created_at')->get();

        $reportData = array();
        foreach ($products as $key=>$product) {
            $rating = count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0;
            $reportData[$key] = array(
                'Product Name' => Str::limit($product->name, 20),
                'Product Unit Price' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($product->unit_price)),
                'Total Amount Sold' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency(isset($product->order_details[0]->total_sold_amount) ? $product->order_details[0]->total_sold_amount : 0)),
                'Average Product Value' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency((isset($product->order_details[0]->total_sold_amount) ? $product->order_details[0]->total_sold_amount : 0) / (isset($product->order_details[0]->product_quantity) ? $product->order_details[0]->product_quantity : 1))),
                'Current Stock Amount' => $product->product_type == 'digital' ? ($product->status==1 ? \App\CPU\translate('available') : \App\CPU\translate('not_available')) : $product->current_stock,
                'Average Ratings' => $rating.' ('.$product->reviews->count().')',
            );
        }

        return (new FastExcel($reportData))->download('all_product_report.xlsx');
    }

    public function stock_product_report(Request $request)
    {
        $search = $request['search'];
        $sort = $request['sort'] ?? 'ASC';
        $category_id = $request['category_id'] ?? 'all';
        $query_param = ['search' => $search, 'sort' => $sort, 'category_id'=>$category_id];

        $stock_limit = \App\CPU\Helpers::get_business_settings('stock_limit');
        $categories = Category::where(['position'=>0])->get();
        $products = self::stock_product_common_query($request)
            ->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.report.product-stock', compact('products', 'categories','search', 'stock_limit', 'sort','category_id'));
    }

    public function product_stock_export(Request $request)
    {
        $products = self::stock_product_common_query($request)->get();
        $stock_limit = Helpers::get_business_settings('stock_limit');

        $data = array();
        foreach ($products as $product) {
            if($product['current_stock'] >= $stock_limit){
                $stock_msg = 'In-Stock';
            }elseif($product['current_stock']  == 0){
                $stock_msg = 'Out of Stock';
            }else{
                $stock_msg = 'Soon Stock Out';
            }
            $data[] = array(
                'Product Name' => $product->name,
                'Date' => date('d M Y', strtotime($product->updated_at)),
                'Current Stock' => $product->current_stock,
                'Status' => $stock_msg,
            );
        }

        return (new FastExcel($data))->download('out_of_stock_product.xlsx');
    }

    public function stock_product_common_query($request){
        $sort = $request['sort'] ?? 'ASC';
        $category_id = $request['category_id'] ?? 'all';
        return Product::where(['product_type' => 'physical', 'added_by'=>'seller','user_id'=>auth('seller')->id()])
            ->when($category_id && $category_id!='all', function($query) use($category_id) {
                $query->whereJsonContains('category_ids', ["id" => $category_id]);
            })
            ->when($request['search'], function ($q) use ($request) {
                $q->where('name', 'Like', '%' . $request['search'] . '%');
            })
            ->orderBy('current_stock', $sort);
    }
}
