<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\RefundRequest;
use App\Model\RefundTransaction;
use App\Model\Seller;
use App\Model\SellerWallet;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Rap2hpoutre\FastExcel\FastExcel;

class ReportController extends Controller
{
    public function admin_earning(Request $request)
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

        $digital_payment_query = Order::where(['order_status' => 'delivered'])->whereNotIn('payment_method', ['cash', 'cash_on_delivery', 'pay_by_wallet', 'offline_payment']);
        $digital_payment = self::earning_common_query($request, $digital_payment_query)->sum('order_amount');

        $cash_payment_query = Order::where(['order_status' => 'delivered'])->whereIn('payment_method', ['cash', 'cash_on_delivery']);
        $cash_payment = self::earning_common_query($request, $cash_payment_query)->sum('order_amount');

        $wallet_payment_query = Order::where(['order_status' => 'delivered'])->where(['payment_method' => 'pay_by_wallet']);
        $wallet_payment = self::earning_common_query($request, $wallet_payment_query)->sum('order_amount');

        $offline_payment_query = Order::where(['payment_method' => 'offline_payment']);
        $offline_payment = self::earning_common_query($request, $offline_payment_query)->sum('order_amount');

        $total_payment = $cash_payment + $wallet_payment + $digital_payment + $offline_payment;

        $payment_data = [
            'total_payment' => $total_payment,
            'cash_payment' => $cash_payment,
            'wallet_payment' => $wallet_payment,
            'offline_payment' => $offline_payment,
            'digital_payment' => $digital_payment,
        ];

        $filter_data = self::earning_common_filter('admin', $date_type, $from, $to);
        $inhouse_earn = $filter_data['earn_from_order'];
        $shipping_earn = $filter_data['shipping_earn'];
        $admin_commission_earn = $filter_data['commission'];
        $refund_given = $filter_data['refund_given'];
        $discount_given = $filter_data['discount_given'];
        $total_tax = $filter_data['total_tax'];

        $total_inhouse_earning = 0;
        $total_commission = 0;
        $total_shipping_earn = 0;
        $total_discount_given = 0;
        $total_refund_given = 0;
        $total_tax_final = 0;
        $total_earning_statistics = array();
        $total_commission_statistics = array();
        foreach($inhouse_earn as $key=>$earning) {
            $total_inhouse_earning += $earning;
            $total_commission += $admin_commission_earn[$key];
            $total_shipping_earn += $shipping_earn[$key];
            $total_discount_given += $discount_given[$key];
            $total_tax_final += $total_tax[$key];
            $total_refund_given += $refund_given[$key];
            $total_commission_statistics[$key] = $admin_commission_earn[$key];
            $total_earning_statistics[$key] = ($earning+$admin_commission_earn[$key]+$shipping_earn[$key])-$refund_given[$key];
        }

        $total_in_house_products_query = Product::where(['added_by' => 'admin']);
        $total_in_house_products = self::earning_common_query($request, $total_in_house_products_query)->count();

        $total_stores_query = Seller::where(['status' => 'approved']);
        $total_stores = self::earning_common_query($request, $total_stores_query)->count();

        $earning_data = [
            'total_inhouse_earning' => $total_inhouse_earning+$total_discount_given-$total_tax_final,
            'total_commission' => $total_commission,
            'total_shipping_earn' => $total_shipping_earn,
            'total_in_house_products' => $total_in_house_products,
            'total_stores' => $total_stores,
            'total_earning_statistics' => $total_earning_statistics,
            'total_commission_statistics' => $total_commission_statistics,
        ];

        return view('admin-views.report.admin-earning', compact('earning_data', 'inhouse_earn', 'shipping_earn',
            'admin_commission_earn', 'refund_given', 'discount_given', 'total_tax', 'from', 'to', 'date_type', 'payment_data'));
    }

    public function admin_earning_excel_export(Request $request){
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

        $filter_data = self::earning_common_filter('admin', $date_type, $from, $to);
        $inhouse_earn = $filter_data['earn_from_order'];
        $shipping_earn = $filter_data['shipping_earn'];
        $admin_commission_earn = $filter_data['commission'];
        $refund_given = $filter_data['refund_given'];
        $discount_given = $filter_data['discount_given'];
        $total_tax = $filter_data['total_tax'];

        $data = array();
        foreach ($inhouse_earn as $key=>$earning) {
            $inhouse_earning = $earning+$discount_given[$key]-$total_tax[$key];
            $data[] = array(
                'Duration' => $key,
                'In-House Earning' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($inhouse_earning)),
                'Commission Earning' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($admin_commission_earn[$key])),
                'Earn From Shipping' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($shipping_earn[$key])),
                'Discount Given' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($discount_given[$key])),
                'Tax Collected' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($total_tax[$key])),
                'Refund Given' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($refund_given[$key])),
                'Total Earning' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($inhouse_earning+$admin_commission_earn[$key]+$total_tax[$key]+$shipping_earn[$key]-$discount_given[$key]-$refund_given[$key])),
            );
        }

        return (new FastExcel($data))->download('admin-earning.xlsx');
    }

    public function earning_common_filter($type, $date_type, $from, $to){

        if($date_type == 'this_year'){ //this year table
            $number = 12;
            $default_inc = 1;
            $current_start_year = date('Y-01-01');
            $current_end_year = date('Y-12-31');
            $from_year = Carbon::parse($from)->format('Y');

            $this_year = self::earning_same_year($type, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
            return $this_year;

        }elseif($date_type == 'this_month'){ //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d',strtotime($current_month_end));

            $this_month = self::earning_same_month($type, $current_month_start, $current_month_end, $month, $number, $inc);
            return $this_month;

        }elseif($date_type == 'this_week'){
            $this_week = self::earning_this_week($type);
            return $this_week;

        }elseif($date_type == 'custom_date' && !empty($from) && !empty($to)){
            $start_date = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date = Carbon::parse($to)->format('Y-m-d 23:59:59');
            $from_year = Carbon::parse($from)->format('Y');
            $from_month = Carbon::parse($from)->format('m');
            $from_day = Carbon::parse($from)->format('d');
            $to_year = Carbon::parse($to)->format('Y');
            $to_month = Carbon::parse($to)->format('m');
            $to_day = Carbon::parse($to)->format('d');

            if($from_year != $to_year){
                $different_year = self::earning_different_year($type, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            }elseif($from_month != $to_month){
                $same_year = self::earning_same_year($type, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            }elseif($from_month == $to_month){
                $same_month = self::earning_same_month($type, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function earning_common_query($request, $query){
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

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

    public function earning_same_month($type, $start_date, $end_date, $month_date, $number, $default_inc){
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));

        //earn from order
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(order_amount) - sum(shipping_cost)) as earn_from_order, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $earn_from_order[$day.'-'.$month] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['day'] == $inc) {
                    $earn_from_order[$day.'-'.$month] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shipping_earns = Order::whereHas('delivery_man', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->where(['order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(shipping_cost) as shipping_earn, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $shipping_earn[$day.'-'.$month] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['day'] == $inc) {
                    $shipping_earn[$day.'-'.$month] = $match['shipping_earn'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(admin_commission) as commission, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $commission[$day.'-'.$month] = 0;
            foreach ($commissions as $match) {
                if ($match['day'] == $inc) {
                    $commission[$day.'-'.$month] = $match['commission'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(discount_amount) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $discount_given[$day.'-'.$month] = 0;
            foreach ($discounts_given as $match) {
                if ($match['day'] == $inc) {
                    $discount_given[$day.'-'.$month] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['seller_is'=> $type, 'status'=>'disburse'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $total_tax[$day.'-'.$month] = 0;
            foreach ($taxes as $match) {
                if ($match['day'] == $inc) {
                    $total_tax[$day.'-'.$month] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $refund_given[$day.'-'.$month] = 0;
            foreach ($refunds as $match) {
                if ($match['day'] == $inc) {
                    $refund_given[$day.'-'.$month] = $match['refund_amount'];
                }
            }
        }

        $data = array(
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
        );
        return $data;
    }

    public function earning_this_week($type){
        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        //earn from order
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('(sum(order_amount) - sum(shipping_cost)) as earn_from_order'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $earn_from_order[$day_name[$inc]] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $earn_from_order[$day_name[$inc]] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shipping_earns = Order::whereHas('delivery_man', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->where(['order_status'=>'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(shipping_cost) as shipping_earn'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $shipping_earn[$day_name[$inc]] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $shipping_earn[$day_name[$inc]] = $match['shipping_earn'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(admin_commission) as commission'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $commission[$day_name[$inc]] = 0;
            foreach ($commissions as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $commission[$day_name[$inc]] = $match['commission'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(discount_amount) as discount_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $discount_given[$day_name[$inc]] = 0;
            foreach ($discounts_given as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $discount_given[$day_name[$inc]] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status'=>'disburse', 'seller_is'=> $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(tax) as total_tax'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $total_tax[$day_name[$inc]] = 0;
            foreach ($taxes as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $total_tax[$day_name[$inc]] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereBetween('updated_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->select(
                DB::raw('sum(amount) as refund_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $refund_given[$day_name[$inc]] = 0;
            foreach ($refunds as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $refund_given[$day_name[$inc]] = $match['refund_amount'];
                }
            }
        }

        $data = array(
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
        );
        return $data;
    }

    public function earning_same_year($type, $start_date, $end_date, $from_year, $number, $default_inc){

        //earn from order
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(order_amount) - sum(shipping_cost)) as earn_from_order, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $earn_from_order[$month.'-'.$from_year] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['month'] == $inc) {
                    $earn_from_order[$month.'-'.$from_year] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn
        $shipping_earns = Order::whereHas('delivery_man', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->where(['order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(shipping_cost) as shipping_earn, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $shipping_earn[$month.'-'.$from_year] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['month'] == $inc) {
                    $shipping_earn[$month.'-'.$from_year] = $match['shipping_earn'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(admin_commission) as commission, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $commission[$month.'-'.$from_year] = 0;
            foreach ($commissions as $match) {
                if ($match['month'] == $inc) {
                    $commission[$month.'-'.$from_year] = $match['commission'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(discount_amount) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $discount_given[$month.'-'.$from_year] = 0;
            foreach ($discounts_given as $match) {
                if ($match['month'] == $inc) {
                    $discount_given[$month.'-'.$from_year] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status'=>'disburse', 'seller_is'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $total_tax[$month.'-'.$from_year] = 0;
            foreach ($taxes as $match) {
                if ($match['month'] == $inc) {
                    $total_tax[$month.'-'.$from_year] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $refund_given[$month.'-'.$from_year] = 0;
            foreach ($refunds as $match) {
                if ($match['month'] == $inc) {
                    $refund_given[$month.'-'.$from_year] = $match['refund_amount'];
                }
            }
        }

        $data = array(
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
        );
        return $data;
    }

    public function earning_different_year($type, $start_date, $end_date, $from_year, $to_year){

        //earn from order for different year
        $earn_from_orders = Order::where(['order_status'=>'delivered', 'seller_is'=>$type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('(sum(order_amount) - sum(shipping_cost)) as earn_from_order, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))->latest('updated_at')->get();


        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $earn_from_order[$inc] = 0;
            foreach ($earn_from_orders as $match) {
                if ($match['year'] == $inc) {
                    $earn_from_order[$inc] = $match['earn_from_order'];
                }
            }
        }

        //shipping earn for custom same year
        $shipping_earns = Order::whereHas('delivery_man', function ($query) use($type){
                $query->when($type=='admin', function ($query){
                    $query->where('seller_id', '0');
                })
                ->when($type=='seller', function ($query){
                    $query->where('seller_id', '!=', '0');
                });
            })
            ->where(['order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(shipping_cost) as shipping_earn, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $shipping_earn[$inc] = 0;
            foreach ($shipping_earns as $match) {
                if ($match['year'] == $inc) {
                    $shipping_earn[$inc] = $match['shipping_earn'];
                }
            }
        }

        //commission
        $commissions = Order::where(['seller_is'=>'seller', 'order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(admin_commission) as commission, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $commission[$inc] = 0;
            foreach ($commissions as $match) {
                if ($match['year'] == $inc) {
                    $commission[$inc] = $match['commission'];
                }
            }
        }

        //discount_given
        $discounts_given = Order::where(['discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->when($type=='admin', function ($query){
                $query->where('coupon_discount_bearer', 'inhouse');
            })
            ->when($type=='seller', function ($query){
                $query->where('coupon_discount_bearer', 'seller');
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(discount_amount) as discount_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $discount_given[$inc] = 0;
            foreach ($discounts_given as $match) {
                if ($match['year'] == $inc) {
                    $discount_given[$inc] = $match['discount_amount'];
                }
            }
        }

        //vat/tax
        $taxes = OrderTransaction::where(['status'=>'disburse', 'seller_is'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $total_tax[$inc] = 0;
            foreach ($taxes as $match) {
                if ($match['year'] == $inc) {
                    $total_tax[$inc] = $match['total_tax'];
                }
            }
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid', 'paid_by'=> $type])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $refund_given[$inc] = 0;
            foreach ($refunds as $match) {
                if ($match['year'] == $inc) {
                    $refund_given[$inc] = $match['refund_amount'];
                }
            }
        }

        $data = array(
            'earn_from_order' => $earn_from_order,
            'shipping_earn' => $shipping_earn,
            'commission' => $commission,
            'discount_given' => $discount_given,
            'total_tax' => $total_tax,
            'refund_given' => $refund_given,
        );
        return $data;
    }

    public function admin_earning_duration_download_pdf(Request $request){
        $earning_data = $request->except('_token');
        $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $mpdf_view = View::make('admin-views.report.admin-earning-duration-wise-pdf', compact('earning_data', 'company_name','company_email','company_phone','company_web_logo'));
        Helpers::gen_mpdf($mpdf_view, 'admin_earning_', $earning_data['duration']);
    }

    public function seller_earning(Request $request)
    {
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

        $total_seller_query = Seller::where(['status' => 'approved']);
        $total_seller = self::earning_common_query($request, $total_seller_query)->count();

        $all_product_query = Product::where(['added_by' => 'seller']);
        $all_product = self::earning_common_query($request, $all_product_query)->count();

        $rejected_product_query = Product::where(['added_by' => 'seller', 'request_status' => 2]);
        $rejected_product = self::earning_common_query($request, $rejected_product_query)->count();

        $pending_product_query = Product::where(['added_by' => 'seller', 'request_status' => 0]);
        $pending_product = self::earning_common_query($request, $pending_product_query)->count();

        $active_product_query = Product::where(['added_by' => 'seller', 'status' => 1, 'request_status' => 1]);
        $active_product = self::earning_common_query($request, $active_product_query)->count();

        $data = [
            'total_seller' => $total_seller,
            'all_product' => $all_product,
            'rejected_product' => $rejected_product,
            'pending_product' => $pending_product,
            'active_product' => $active_product,
        ];

        $payments = SellerWallet::selectRaw('sum(total_earning) as total_earning, sum(pending_withdraw) as pending_withdraw, sum(withdrawn) as withdrawn')->first();
        $withdrawable_balance = $payments->total_earning - $payments->pending_withdraw;

        $payment_data = [
            'wallet_amount' => $payments->total_earning,
            'withdrawable_balance' => $withdrawable_balance,
            'pending_withdraw' => $payments->pending_withdraw,
            'already_withdrawn' => $payments->withdrawn,
        ];

        $filter_data_chart = self::earning_common_filter('seller', $date_type, $from, $to);
        $seller_earn_chart = $filter_data_chart['earn_from_order'];
        $shipping_earn_chart = $filter_data_chart['shipping_earn'];
        $commission_given_chart = $filter_data_chart['commission'];
        $discount_given_chart = $filter_data_chart['discount_given'];
        $total_tax_chart = $filter_data_chart['total_tax'];
        $refund_given_chart = $filter_data_chart['refund_given'];

        $chart_earning_statistics = array();
        foreach($seller_earn_chart as $key=>$earning) {
            $chart_earning_statistics[$key] = $earning+$shipping_earn_chart[$key]-$refund_given_chart[$key]-$commission_given_chart[$key];
        }

        $filter_data_table = self::seller_earning_common_filter_table($date_type, $from, $to);
        $seller_earn_table = $filter_data_table['seller_earn_table'];
        $shipping_earn_table = $filter_data_table['shipping_earn_table'];
        $commission_given_table = $filter_data_table['commission_given_table'];
        $total_refund_table = $filter_data_table['total_refund_table'];
        $discount_given_table = $filter_data_table['discount_given_table'];
        $discount_given_bearer_admin_table = $filter_data_table['discount_given_bearer_admin_table'];
        $total_tax_table = $filter_data_table['total_tax_table'];

        $table_earning = array(
            'seller_earn_table' => $seller_earn_table,
            'commission_given_table' => $commission_given_table,
            'shipping_earn_table' => $shipping_earn_table,
            'discount_given_table' => $discount_given_table,
            'discount_given_bearer_admin_table' => $discount_given_bearer_admin_table,
            'total_tax_table' => $total_tax_table,
            'total_refund_table' => $total_refund_table,
        );

        $total_seller_earning = 0;
        $total_commission = 0;
        $total_shipping_earn = 0;
        $total_discount_given = 0;
        $total_refund_given = 0;
        $total_tax = 0;
        foreach($seller_earn_table as $key=>$earning) {
            $shipping_earn = isset($shipping_earn_table[$key]['amount']) ? $shipping_earn_table[$key]['amount'] : 0;
            $commission_given = isset($commission_given_table[$key]['amount']) ? $commission_given_table[$key]['amount'] : 0;
            $discount_given = isset($discount_given_table[$key]['amount']) ? $discount_given_table[$key]['amount'] : 0;
            $tax = isset($total_tax_table[$key]['amount']) ? $total_tax_table[$key]['amount'] : 0;
            $refund_given = isset($total_refund_table[$key]['amount']) ? $total_refund_table[$key]['amount'] : 0;

            $total_seller_earning += $earning['amount'];
            $total_commission += $commission_given;
            $total_shipping_earn += $shipping_earn;
            $total_discount_given += $discount_given;
            $total_tax += $tax;
            $total_refund_given += $refund_given;
        }
        $total_earning = ($total_seller_earning+$total_shipping_earn)-($total_refund_given+$total_commission);

        return view('admin-views.report.seller-earning', compact('data', 'payment_data', 'table_earning', 'total_earning', 'chart_earning_statistics', 'from', 'to', 'date_type'));
    }

    public function seller_earning_excel_export(Request $request){
        $from         = $request['from'];
        $to           = $request['to'];
        $date_type    = $request['date_type'] ?? 'this_year';

        $filter_data = self::seller_earning_common_filter_table($date_type, $from, $to);
        $seller_earn_table = $filter_data['seller_earn_table'];
        $shipping_earn_table = $filter_data['shipping_earn_table'];
        $commission_given_table = $filter_data['commission_given_table'];
        $total_refund_table = $filter_data['total_refund_table'];
        $discount_given_table = $filter_data['discount_given_table'];
        $discount_given_bearer_admin_table = $filter_data['discount_given_bearer_admin_table'];
        $total_tax_table = $filter_data['total_tax_table'];

        $data = array();
        foreach ($seller_earn_table as $key=>$seller_earn) {
            $shipping_earn = isset($shipping_earn_table[$key]['amount']) ? $shipping_earn_table[$key]['amount'] : 0;
            $commission_given = isset($commission_given_table[$key]['amount']) ? $commission_given_table[$key]['amount'] : 0;
            $discount_given = isset($discount_given_table[$key]['amount']) ? $discount_given_table[$key]['amount'] : 0;
            $discount_given_bearer_admin = isset($discount_given_bearer_admin_table[$key]['amount']) ? $discount_given_bearer_admin_table[$key]['amount'] : 0;
            $total_tax = isset($total_tax_table[$key]['amount']) ? $total_tax_table[$key]['amount'] : 0;
            $refund_given = isset($total_refund_table[$key]['amount']) ? $total_refund_table[$key]['amount'] : 0;
            $total_earn_from_order = $seller_earn['amount']+$discount_given_bearer_admin+$discount_given-$total_tax;

            $data[] = array(
                'Seller_Info' => $seller_earn['name'],
                'Earn From Order' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($total_earn_from_order)),
                'Earn From Shipping' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($shipping_earn)),
                'Commission Given' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($commission_given)),
                'Discount Given' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($discount_given)),
                'Tax Collected' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($total_tax)),
                'Refund Given' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($refund_given)),
                'Total Earning' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($total_earn_from_order+$shipping_earn+$total_tax-$discount_given-$refund_given-$commission_given)),
            );
        }

        return (new FastExcel($data))->download('seller-earning.xlsx');
    }

    public function seller_earning_common_filter_table($date_type, $from, $to)
    {

        if ($date_type == 'this_year') { //this year table
            $start_date = date('Y-01-01');
            $end_date = date('Y-12-31');

            $this_year = self::seller_earning_query_table($start_date, $end_date);
            return $this_year;

        }elseif($date_type == 'this_month'){ //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');

            $this_month = self::seller_earning_query_table($current_month_start, $current_month_end);
            return $this_month;

        }elseif($date_type == 'this_week'){

            $this_week = self::seller_earning_query_table(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
            return $this_week;

        }elseif($date_type == 'custom_date' && !empty($from) && !empty($to)) {
            $start_date_custom = Carbon::parse($from)->format('Y-m-d 00:00:00');
            $end_date_custom = Carbon::parse($to)->format('Y-m-d 23:59:59');

            $custom_date = self::seller_earning_query_table($start_date_custom, $end_date_custom);
            return $custom_date;
        }
    }

    /**
    *   seller earning query for table
     */
    public function seller_earning_query_table($start_date, $end_date){
        //seller earn and admin commision
        $seller_earnings_commission = Order::where(['order_status'=>'delivered', 'seller_is'=>'seller'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('seller_id, (sum(order_amount) - sum(shipping_cost)) as earn_from_order, sum(admin_commission) as admin_commission, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')->latest('updated_at')->get();

        $seller_earn_table = array();
        $commission_given_table = array();
        foreach ($seller_earnings_commission as $data) {
            $seller = Seller::find($data->seller_id);
            $seller_earn_table[$data->seller_id] = array(
                'seller_id'=> $data->seller_id,
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->earn_from_order
            );

            $commission_given_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->admin_commission
            );
        }

        //discount_given_bearer_admin
        $discount_given_bearer_admin = Order::where(['coupon_discount_bearer'=>'inhouse', 'discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(discount_amount) as discount_amount, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $discount_given_bearer_admin_table = array();
        foreach ($discount_given_bearer_admin as $data) {
            $seller = Seller::find($data->seller_id);
            $discount_given_bearer_admin_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->discount_amount
            );
        }

        //shipping earn
        $shipping_earns = Order::whereHas('delivery_man', function ($query){
            $query->where('seller_id', '!=', '0');
        })
            ->where(['order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(shipping_cost) as shipping_earn, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $shipping_earn_table = array();
        foreach ($shipping_earns as $data) {
            $seller = Seller::find($data->seller_id);
            $shipping_earn_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->shipping_earn
            );
        }

        //discount_given
        $discounts_given = Order::where(['coupon_discount_bearer'=>'seller', 'discount_type'=>'coupon_discount','order_status'=>'delivered'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(discount_amount) as discount_amount, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $discount_given_table = array();
        foreach ($discounts_given as $data) {
            $seller = Seller::find($data->seller_id);
            $discount_given_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->discount_amount
            );
        }

        //vat/tax
        $taxes = OrderTransaction::where(['seller_is'=>'seller', 'status'=>'disburse'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(tax) as total_tax, seller_id, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy('seller_id')
            ->latest('updated_at')->get();

        $total_tax_table = array();
        foreach ($taxes as $data) {
            $seller = Seller::find($data->seller_id);
            $total_tax_table[$data->seller_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->total_tax
            );
        }

        //refund given
        $refunds = RefundTransaction::where(['payment_status'=>'paid','paid_by'=>'seller'])
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date)
            ->selectRaw('sum(amount) as refund_amount, payer_id, YEAR(updated_at) year')
            ->groupBy('payer_id')
            ->latest('updated_at')->get();

        $total_refund_table = array();
        foreach ($refunds as $data) {
            $seller = Seller::find($data->payer_id);
            $total_refund_table[$data->payer_id] = array(
                'name'=> !empty($seller) ? $seller->f_name.' '.$seller->l_name : '',
                'amount'=> $data->refund_amount
            );
        }

        foreach($total_refund_table as $key=>$data){
            if(!array_key_exists($key, $seller_earn_table)){
                $seller_earn_table[$key] = array(
                    'name' => $data['name'],
                    'amount' => 0,
                );
            }
        }

        $data = array(
            'seller_earn_table' => $seller_earn_table,
            'commission_given_table' => $commission_given_table,
            'shipping_earn_table' => $shipping_earn_table,
            'discount_given_table' => $discount_given_table,
            'discount_given_bearer_admin_table' => $discount_given_bearer_admin_table,
            'total_tax_table' => $total_tax_table,
            'total_refund_table' => $total_refund_table,
        );
        return $data;
    }

    public function set_date(Request $request)
    {
        $from = $request['from'];
        $to = $request['to'];

        session()->put('from_date', $from);
        session()->put('to_date', $to);

        $previousUrl = strtok(url()->previous(), '?');
        return redirect()->to($previousUrl . '?' . http_build_query(['from_date' => $request['from'], 'to_date' => $request['to']]))->with(['from' => $from, 'to' => $to]);
    }
}
