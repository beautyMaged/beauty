<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\RefundTransaction;
use App\Model\Seller;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use App\Model\OrderDetail;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class SellerProductSaleReportController extends Controller
{
    public function seller_report(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $seller_id = $request['seller_id'] ?? 'all';
        $query_param = ['seller_id'=>$seller_id, 'date_type'=>$date_type, 'from'=>$from, 'to'=>$to, 'search'=>$search];

        $sellers = Seller::where(['status'=>'approved'])->get();

        $chart_data = self::seller_report_chart_filter($request);

        $orders_query = Order::with(['seller.shop'])
            ->selectRaw('seller_id, sum(order_amount) as total_order_amount, sum(admin_commission) as total_admin_commission')
            ->where(['seller_is'=> 'seller', 'order_status'=>'delivered'])
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('seller.shop', function ($q) use($search){
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        $orders = self::date_wise_common_filter($orders_query, $date_type, $from, $to)->groupBy('seller_id')->paginate(Helpers::pagination_limit())->appends($query_param);

        $refunds_query = RefundTransaction::where(['paid_by'=>'seller', 'transaction_type'=>'Refund'])
            ->selectRaw('payer_id, sum(amount) as total_refund_amount')
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('payer_id', $seller_id);
            });
        $refunds = self::date_wise_common_filter($refunds_query, $date_type, $from, $to)->groupBy('payer_id')->get()->toArray();

        $product_query = Product::where(['added_by'=> 'seller'])
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('user_id', $seller_id);
            });
        $total_product = self::create_date_wise_common_filter($product_query, $date_type, $from, $to)->count();

        $ongoing_order_query = Order::where('seller_is','seller')
            ->whereIn('order_status',['out_for_delivery','processing','confirmed', 'pending'])
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            });
        $ongoing_order = self::date_wise_common_filter($ongoing_order_query, $date_type, $from, $to)->count();

        $cancel_order_query = Order::where('seller_is','seller')
            ->whereIn('order_status',['canceled','failed','returned'])
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            });
        $canceled_order = self::date_wise_common_filter($cancel_order_query, $date_type, $from, $to)->count();

        $delivered_order_query = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            });
        $delivered_order = self::date_wise_common_filter($delivered_order_query, $date_type, $from, $to)->count();

        $deliveryman_query = DeliveryMan::when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })
        ->when($seller_id == 'all', function ($query) use ($seller_id) {
            $query->where('seller_id', '!=', '0');
        });
        $deliveryman = self::date_wise_common_filter($deliveryman_query, $date_type, $from, $to)->count();

        //total store earning calculate start
        $seller_earn_commission_query = Order::where(['seller_is'=>'seller','order_status'=>'delivered'])
            ->selectRaw('(sum(order_amount) - sum(shipping_cost)) as earn_from_order, sum(admin_commission) as admin_commission')
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            });
        $seller_earn_commission = self::date_wise_common_filter($seller_earn_commission_query, $date_type, $from, $to)->first();

        $shipping_earn_query = Order::whereHas('delivery_man', function ($query){
                $query->where('seller_id', '!=', '0');
            })
            ->where(['order_status'=>'delivered'])
            ->selectRaw('sum(shipping_cost) as shipping_earn');
        $shipping_earn = self::date_wise_common_filter($shipping_earn_query, $date_type, $from, $to)->first();

        $refund_query = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> 'seller'])
            ->selectRaw('sum(amount) as refund_amount')
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('payer_id', $seller_id);
            });
        $refund = self::date_wise_common_filter($refund_query, $date_type, $from, $to)->first();

        $total_store_earning = $seller_earn_commission->earn_from_order+$shipping_earn->shipping_earn - $seller_earn_commission->admin_commission-$refund->refund_amount;
        //total store earning end

        return view('admin-views.report.seller-product-sale', compact('sellers', 'refunds', 'total_product', 'deliveryman',
            'delivered_order', 'total_store_earning', 'ongoing_order', 'canceled_order', 'chart_data', 'orders', 'seller_id', 'date_type', 'from', 'to', 'search'));
    }

    public function seller_report_chart_filter($request)
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

            $this_year = self::seller_report_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
            return $this_year;

        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            $this_month = self::seller_report_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
            return $this_month;

        } elseif ($date_type == 'this_week') {
            $this_week = self::seller_report_this_week($request);
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
                $different_year = self::seller_report_different_year($request, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            } elseif ($from_month != $to_month) {
                $same_year = self::seller_report_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            } elseif ($from_month == $to_month) {
                $same_month = self::seller_report_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function seller_report_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {
        $orders = self::seller_report_chart_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $order_amount[$month . '-' . $from_year] = 0;
            foreach ($orders as $match) {
                if ($match['month'] == $inc) {
                    $order_amount[$month . '-' . $from_year] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function seller_report_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));

        $orders = self::seller_report_chart_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $order_amount[$day . '-' . $month] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $inc) {
                    $order_amount[$day . '-' . $month] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function seller_report_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::seller_report_chart_common_query($request, $start_date, $end_date)
            ->select(
                DB::raw('sum(order_amount) as order_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $order_amount[$day_name[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $order_amount[$day_name[$inc]] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function seller_report_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {
        $orders = self::seller_report_chart_common_query($request, $start_date, $end_date)
            ->selectRaw('sum(order_amount) as order_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $order_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['year'] == $inc) {
                    $order_amount[$inc] = $match['order_amount'];
                }
            }
        }

        return array(
            'order_amount' => $order_amount,
        );
    }

    public function seller_report_chart_common_query($request, $start_date, $end_date)
    {
        $seller_id = $request['seller_id'] ?? 'all';

        $query = Order::where('seller_is', 'seller')
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date);

        return $query;
    }

    public function seller_report_excel(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $seller_id = $request['seller_id'] ?? 'all';

        $orders_query = Order::with(['seller.shop'])
            ->selectRaw('seller_id, sum(order_amount) as total_order_amount, sum(admin_commission) as total_admin_commission')
            ->withSum('details', 'tax')
            ->where(['seller_is'=> 'seller', 'order_status'=>'delivered'])
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('seller_id', $seller_id);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('seller.shop', function ($q) use($search){
                    $q->where('name', 'like', "%{$search}%");
                });
            });
        $orders = self::date_wise_common_filter($orders_query, $date_type, $from, $to)->groupBy('seller_id')->get();

        $refunds_query = RefundTransaction::where(['paid_by'=>'seller', 'transaction_type'=>'Refund'])
            ->selectRaw('payer_id, sum(amount) as total_refund_amount')
            ->when($seller_id && $seller_id != 'all', function ($query) use ($seller_id) {
                $query->where('payer_id', $seller_id);
            });
        $refunds = self::date_wise_common_filter($refunds_query, $date_type, $from, $to)->groupBy('payer_id')->get();

        $reportData = array();
        foreach ($orders as $key=>$order) {
            $reportData[$key] = array(
                'Seller Info' => isset($order->seller->shop) ? $order->seller->shop->name : 'Not Found',
                'Total Order' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->total_order_amount)),
                'Commission' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($order->total_admin_commission)),
            );
            $arr= array();
            if($refunds) {
                foreach ($refunds as $refund) {
                    $arr += array(
                        $refund['payer_id'] => $refund['total_refund_amount']
                    );
                }
            }
            $reportData[$key]['Refund Rate'] = array_key_exists($order->seller_id, $arr) ? (number_format(($arr[$order->seller_id]/$order->total_order_amount)*100, 2).'%') : '0%';
        }

        return (new FastExcel($reportData))->download('seller_report.xlsx');
    }

    public function date_wise_common_filter($query, $date_type, $from, $to)
    {
        return $query->when(($date_type == 'this_year'), function ($query) {
            return $query->whereYear('updated_at', date('Y'));
        })
            ->when(($date_type == 'this_month'), function ($query) {
                return $query->whereMonth('updated_at', date('m'))
                    ->whereYear('updated_at', date('Y'));
            })
            ->when(($date_type == 'this_week'), function ($query) {
                return $query->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            })
            ->when(($date_type == 'custom_date' && !is_null($from) && !is_null($to)), function ($query) use ($from, $to) {
                return $query->whereDate('updated_at', '>=', $from)
                    ->whereDate('updated_at', '<=', $to);
            });
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
}
