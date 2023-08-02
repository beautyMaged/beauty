<?php

namespace App\Http\Controllers\Admin;

use App\CPU\BackEndHelper;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Seller;
use App\Model\Shop;
use App\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Rap2hpoutre\FastExcel\FastExcel;

class TransactionReportController extends Controller
{
    public function order_transaction_list(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';
        $payment_status = $request['payment_status'] ?? 'all';

        $transactions = self::order_transaction_table_data_filter($request);

        $query_param = ['search' => $search, 'status' => $status, 'customer_id' => $customer_id, 'date_type' => $date_type, 'from' => $from, 'to' => $to];
        $transactions = $transactions->latest('updated_at')->paginate(Helpers::pagination_limit())->appends($query_param);

        $order_transaction_chart = self::order_transaction_chart_filter($request);

        $customers = User::whereNotIn('id', [0])->get();
        $sellers = Seller::where(['status' => 'approved'])->get();

        $in_house_orders_query = Order::where(['seller_is' => 'admin']);
        $in_house_orders = self::order_transaction_count_query($in_house_orders_query, $request)->count();

        $seller_orders_query = Order::where(['seller_is' => 'seller']);
        $seller_orders = self::order_transaction_count_query($seller_orders_query, $request)->count();
        $total_orders = $in_house_orders + $seller_orders;


        $total_in_house_product_query = Product::where(['added_by' => 'admin'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1]);
                });
            });
        $total_in_house_products = self::date_wise_common_filter($total_in_house_product_query, $date_type, $from, $to)->count();

        $total_seller_product_query = Product::where(['added_by' => 'seller'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['user_id' => $seller_id]);
                });
            });
        $total_seller_products = self::date_wise_common_filter($total_seller_product_query, $date_type, $from, $to)->count();

        $total_stores_query = Shop::when($seller_id != 'all', function ($query) use ($seller_id) {
            $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                $q->where(['seller_id' => $seller_id]);
            });
        });
        $total_stores = self::date_wise_common_filter($total_stores_query, $date_type, $from, $to)->count();

        $order_data = [
            'total_orders' => $total_orders,
            'in_house_orders' => $in_house_orders,
            'seller_orders' => $seller_orders,
            'total_in_house_products' => $total_in_house_products,
            'total_seller_products' => $total_seller_products,
            'total_stores' => $total_stores,
        ];

        $digital_payment_query = Order::whereNotIn('payment_method', ['cash', 'cash_on_delivery', 'pay_by_wallet', 'offline_payment']);
        $digital_payment = self::order_transaction_piechart_query($request, $digital_payment_query)->sum('order_amount');

        $cash_payment_query = Order::whereIn('payment_method', ['cash', 'cash_on_delivery']);
        $cash_payment = self::order_transaction_piechart_query($request, $cash_payment_query)->sum('order_amount');

        $wallet_payment_query = Order::where(['payment_method' => 'pay_by_wallet']);
        $wallet_payment = self::order_transaction_piechart_query($request, $wallet_payment_query)->sum('order_amount');

        $offline_payment_query = Order::where(['payment_method' => 'offline_payment']);
        $offline_payment = self::order_transaction_piechart_query($request, $offline_payment_query)->sum('order_amount');

        $total_payment = $cash_payment + $wallet_payment + $digital_payment +$offline_payment;

        $payment_data = [
            'digital_payment' => $digital_payment,
            'cash_payment' => $cash_payment,
            'wallet_payment' => $wallet_payment,
            'offline_payment' => $offline_payment,
            'total_payment' => $total_payment,
        ];

        return view('admin-views.transaction.order-list', compact('customers', 'sellers', 'transactions', 'search', 'status',
            'from', 'to', 'customer_id', 'seller_id', 'payment_status', 'order_data', 'date_type', 'payment_data', 'order_transaction_chart'));
    }

    public function order_transaction_count_query($query, $request)
    {
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $query_data = $query->when($status != 'all', function ($query) use ($status) {
                return $query->whereHas('order_transaction', function ($q) use ($status) {
                    $q->where(['status' => $status]);
                });
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });

        return self::date_wise_common_filter($query_data, $date_type, $from, $to);
    }

    public function order_transaction_piechart_query($request, $query)
    {
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $query_data = $query->where(['payment_status' => 'paid'])
            ->whereHas('order_transaction', function ($query) use ($status) {
                $query->when($status != 'all', function ($query) use ($status) {
                    $query->where(['status' => $status]);
                });
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });

        return self::date_wise_common_filter($query_data, $date_type, $from, $to);
    }

    public function order_transaction_chart_filter($request)
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

            $this_year = self::order_transaction_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
            return $this_year;

        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            $this_month = self::order_transaction_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
            return $this_month;

        } elseif ($date_type == 'this_week') {
            $this_week = self::order_transaction_this_week($request);
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
                $different_year = self::order_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            } elseif ($from_month != $to_month) {
                $same_year = self::order_transaction_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            } elseif ($from_month == $to_month) {
                $same_month = self::order_transaction_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function order_transaction_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));
        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
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

    public function order_transaction_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
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

    public function order_transaction_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
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

    public function order_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {

        $orders = self::order_transaction_date_common_query($request, $start_date, $end_date)
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

    public function order_transaction_date_common_query($request, $start_date, $end_date)
    {
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';

        $query = Order::with('order_transaction')
            ->where('payment_status', 'paid')
            ->when($status != 'all', function ($query) use ($status) {
                $query->whereHas('order_transaction', function ($query) use ($status) {
                    $query->where(['status' => $status]);
                });
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            })
            ->whereDate('updated_at', '>=', $start_date)
            ->whereDate('updated_at', '<=', $end_date);

        return $query;
    }

    public function order_transaction_table_data_filter($request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $transaction_query = OrderTransaction::with(['seller.shop', 'customer', 'order.delivery_man', 'order'])
            ->with(['order_details'=> function ($query) {
                $query->selectRaw("*, sum(qty*price) as order_details_sum_price, sum(discount) as order_details_sum_discount")
                    ->groupBy('order_id');
            }])
            ->when($search, function ($q) use ($search) {
                $q->orWhere('order_id', 'like', "%{$search}%")
                    ->orWhere('transaction_id', 'like', "%{$search}%");
            })
            ->when($status != 'all', function ($query) use ($status) {
                $query->where(['status' => $status]);
            })
            ->when($customer_id != 'all', function ($query) use ($customer_id) {
                $query->where('customer_id', $customer_id);
            })
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['seller_id' => 1, 'seller_is' => 'admin']);
                })->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id, 'seller_is' => 'seller']);
                });
            });
        $transactions = self::date_wise_common_filter($transaction_query, $date_type, $from, $to);

        return $transactions;
    }

    /**
     * Order transaction report export by excel
     */
    public function order_transaction_export_excel(Request $request)
    {

        $transactions = self::order_transaction_table_data_filter($request)->latest('updated_at')->get();

        $tranData = array();
        foreach ($transactions as $tran) {
            $admin_net_income = 0;
            if ($tran['seller_is'] == 'admin') {
                $shop_name = Helpers::get_business_settings('company_name');
                $admin_net_income += $tran['order_amount'] + $tran['delivery_charge'] + $tran['tax'];
            } else {
                $shop_name = isset($tran->seller->shop->name) ? $tran->seller->shop->name : 'Not Found';
            }

            $seller_net_income = 0;
            if(isset($tran->order->delivery_man) && $tran->order->delivery_man->seller_id != '0'){
                $seller_net_income += $tran['delivery_charge'];
            }

            $coupon_discount_seller = $tran['order']['coupon_discount_bearer'] == 'seller' ? $tran['order']['discount_amount'] : 0;
            if($tran['seller_is'] == 'seller'){
                $seller_net_income += ($tran['order_amount'] + $tran['tax'] - $tran['admin_commission'] - $coupon_discount_seller);
            }

            $admin_net_income += $tran['admin_commission'];

            $tranData[] = array(
                'Order ID' => $tran->order_id,
                'Shop Name' => $shop_name,
                'Customer Name' => $tran->customer ? $tran->customer->f_name . ' ' . $tran->customer->l_name : 'not_found',
                'Total Product Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->order_details[0]->order_details_sum_price)),
                'Product Discount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->order_details[0]->order_details_sum_discount)),
                'Coupon Discount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->order->discount_amount)),
                'Discounted Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->order_amount)),
                'Tax' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->tax)),
                'Delivery Charge' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->delivery_charge)),
                'Order Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->order->order_amount)),
                'Delivered By' => $tran->delivered_by,
                'Admin Discount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency(($tran->order->coupon_discount_bearer == 'inhouse' && $tran->order->discount_type == 'coupon_discount') ? $tran->order->discount_amount : 0)),
                'Seller Discount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency(($tran->order->coupon_discount_bearer == 'seller' && $tran->order->discount_type == 'coupon_discount') ? $tran->order->discount_amount : 0)),
                'Admin Commission' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($tran->admin_commission)),
                'Admin Net Income' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($admin_net_income)),
                'Seller Net Income' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($seller_net_income)),
                'Payment Method' => $tran->payment_method,
                'Status' => $tran->status,
            );
        }

        return (new FastExcel($tranData))->download('Order_Transaction_details.xlsx');

    }

    /**
     * order transaction summary pdf
     */
    public function order_transaction_summary_pdf(Request $request)
    {

        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $from = $request['from'];
        $to = $request['to'];
        $customer_id = $request['customer_id'] ?? 'all';
        $seller_id = $request['seller_id'] ?? 'all';
        $status = $request['status'] ?? 'all';
        $date_type = $request['date_type'] ?? 'this_year';

        $duration = str_replace('_', ' ', $date_type);
        if ($date_type == 'custom_date') {
            $duration = 'From ' . $from . ' To ' . $to;
        }

        $seller_info = $seller_id == 'all' || $seller_id == 'inhouse' ? $seller_id : Shop::where('seller_id', $seller_id)->name;
        $customer_info = 'all';
        if ($customer_id != 'all') {
            $customer = User::select()->find($customer_id);
            $customer_info = $customer->f_name . ' ' . $customer->l_name;
        }

        $transactions = self::order_transaction_table_data_filter($request)->latest('updated_at')->get();

        $total_ordered_product_price = 0;
        $total_product_discount = 0;
        $total_coupon_discount = 0;
        $total_discounted_amount = 0;
        $total_tax = 0;
        $total_delivery_charge = 0;
        $total_order_amount = 0;
        $total_admin_discount = 0;
        $total_seller_discount = 0;
        $total_admin_commission = 0;
        $total_admin_net_income = 0;
        $total_seller_net_income = 0;
        foreach ($transactions as $transaction) {
            $total_ordered_product_price += $transaction->order_details[0]->order_details_sum_price;
            $total_product_discount += $transaction->order_details[0]->order_details_sum_discount;
            $total_coupon_discount += $transaction->order->discount_amount;
            $total_discounted_amount += $transaction->order_amount;
            $total_tax += $transaction->tax;
            $total_delivery_charge += $transaction->delivery_charge;
            $total_order_amount += $transaction->order->order_amount;

            $total_admin_discount += ($transaction->order->coupon_discount_bearer == 'inhouse' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0;
            $total_seller_discount += ($transaction->order->coupon_discount_bearer == 'seller' && $transaction->order->discount_type == 'coupon_discount') ? $transaction->order->discount_amount : 0;
            $total_admin_commission += $transaction->admin_commission;

            $admin_net_income = 0;
            if ($transaction['seller_is'] == 'admin') {
                $admin_net_income += $transaction['order_amount'] + $transaction['tax'];
            }

            $coupon_discount_seller = $transaction['order']['coupon_discount_bearer'] == 'seller' ? $transaction['order']['discount_amount'] : 0;
            if(isset($transaction->order->delivery_man) && $transaction->order->delivery_man->seller_id == '0'){
                $admin_net_income += $transaction['delivery_charge'];
            }
            $admin_net_income += $transaction['admin_commission'];
            $total_admin_net_income += $admin_net_income;
            $total_seller_net_income += ($transaction['order_amount'] + $transaction['tax'] - $transaction['admin_commission'] - $coupon_discount_seller);
        }

        $in_house_orders_query = Order::where(['seller_is' => 'admin']);
        $in_house_orders = self::order_transaction_count_query($in_house_orders_query, $request)->count();

        $seller_orders_query = Order::where(['seller_is' => 'seller']);
        $seller_orders = self::order_transaction_count_query($seller_orders_query, $request)->count();
        $total_orders = $in_house_orders + $seller_orders;


        $total_in_house_product_query = Product::where(['added_by' => 'admin'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id == 'inhouse', function ($q) {
                    $q->where(['user_id' => 1]);
                });
            });
        $total_in_house_products = self::date_wise_common_filter($total_in_house_product_query, $date_type, $from, $to)->count();

        $total_seller_product_query = Product::where(['added_by' => 'seller'])
            ->when($seller_id != 'all', function ($query) use ($seller_id) {
                $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                    $q->where(['seller_id' => $seller_id]);
                });
            });
        $total_seller_products = self::date_wise_common_filter($total_seller_product_query, $date_type, $from, $to)->count();

        $total_stores_query = Shop::when($seller_id != 'all', function ($query) use ($seller_id) {
            $query->when($seller_id != 'inhouse', function ($q) use ($seller_id) {
                $q->where(['seller_id' => $seller_id]);
            });
        });
        $total_stores = self::date_wise_common_filter($total_stores_query, $date_type, $from, $to)->count();

        $data = array(
            'total_ordered_product_price' => $total_ordered_product_price,
            'total_product_discount' => $total_product_discount,
            'total_coupon_discount' => $total_coupon_discount,
            'total_discounted_amount' => $total_discounted_amount,
            'total_tax' => $total_tax,
            'total_delivery_charge' => $total_delivery_charge,
            'total_order_amount' => $total_order_amount,
            'total_admin_discount' => $total_admin_discount,
            'total_seller_discount' => $total_seller_discount,
            'total_admin_commission' => $total_admin_commission,
            'total_admin_net_income' => $total_admin_net_income,
            'total_seller_net_income' => $total_seller_net_income,
            'total_orders' => $total_orders,
            'in_house_orders' => $in_house_orders,
            'seller_orders' => $seller_orders,
            'total_in_house_products' => $total_in_house_products,
            'total_seller_products' => $total_seller_products,
            'total_stores' => $total_stores,
        );

        $mpdf_view = View::make('admin-views.transaction.order_transaction_summary_report_pdf', compact('data', 'company_phone', 'company_name', 'company_email', 'company_web_logo', 'status', 'duration', 'seller_info', 'customer_info'));
        Helpers::gen_mpdf($mpdf_view, 'order_transaction_summary_report_', $date_type);

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

    public function pdf_order_wise_transaction(Request $request)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $transaction = OrderTransaction::with(['seller.shop', 'customer', 'order', 'order_details'])
            ->withSum('order_details', 'price')
            ->withSum('order_details', 'discount')
            ->where('order_id', $request->order_id)->first();

        $mpdf_view = View::make('admin-views.transaction.order_wise_pdf', compact('company_phone', 'company_name', 'company_email', 'company_web_logo', 'transaction'));
        Helpers::gen_mpdf($mpdf_view, 'order_transaction_', $request->order_id);

    }

    public function expense_transaction_list(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $query_param = ['search' => $search, 'date_type' => $date_type, 'from' => $from, 'to' => $to];

        $expense_transaction_chart = self::expense_transaction_chart_filter($request);

        $expense_calculate_query = Order::with(['order_transaction', 'coupon'])
            ->where(['coupon_discount_bearer'=> 'inhouse', 'order_status'=>'delivered'])
            ->whereNotIn('coupon_code', ['0', 'NULL'])
            ->whereHas('order_transaction', function ($query) use($search){
                $query->where(['status'=>'disburse']);
            });
        $expense_calculate = self::date_wise_common_filter($expense_calculate_query, $date_type, $from, $to)->latest('updated_at')->get();

        $total_expense = 0;
        $free_delivery = 0;
        $coupon_discount = 0;
        if($expense_calculate){
            foreach ($expense_calculate as $calculate){
                $total_expense += $calculate->discount_amount;
                if(isset($calculate->coupon->coupon_type) && $calculate->coupon->coupon_type == 'free_delivery'){
                    $free_delivery += $calculate->discount_amount;
                }else{
                    $coupon_discount += $calculate->discount_amount;
                }
            }
        }

        $expense_transaction_query = Order::with(['order_transaction', 'coupon'])
            ->where(['coupon_discount_bearer'=> 'inhouse', 'order_status'=>'delivered'])
            ->whereNotIn('coupon_code', ['0', 'NULL'])
            ->whereHas('order_transaction', function ($query) use($search){
                $query->where(['status'=>'disburse'])
                ->when($search, function ($q) use ($search) {
                    $q->Where('order_id', 'like', "%{$search}%")
                        ->orWhere('transaction_id', 'like', "%{$search}%");
                });
            });
        $expense_transactions_table = self::date_wise_common_filter($expense_transaction_query, $date_type, $from, $to);
        $expense_transactions_table = $expense_transactions_table->latest('updated_at')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.transaction.expense-list', compact('expense_transactions_table', 'expense_transaction_chart', 'search', 'from', 'to', 'date_type', 'total_expense', 'free_delivery', 'coupon_discount'));
    }

    /**
     * expense transaction report export by excel
     */
    public function expense_transaction_export_excel(Request $request)
    {
        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';
        $expense_transaction_query = Order::with(['order_transaction', 'coupon'])
            ->where(['coupon_discount_bearer'=> 'inhouse', 'order_status'=>'delivered'])
            ->whereNotIn('coupon_code', ['0', 'NULL'])
            ->whereHas('order_transaction', function ($query) use($search){
                $query->where(['status'=>'disburse'])
                    ->when($search, function ($q) use ($search) {
                        $q->where('transaction_id', 'like', "%{$search}%");
                    });
            });
        $transactions = self::date_wise_common_filter($expense_transaction_query, $date_type, $from, $to)->latest('updated_at')->get();

        $tranData = array();
        foreach ($transactions as $transaction) {
            $tranData[] = array(
                'XID' => $transaction->order_transaction->transaction_id,
                'Transaction Date' => date_format($transaction->order_transaction->updated_at, 'd F Y'),
                'Order ID' => $transaction->id,
                'Expense Amount' => BackEndHelper::set_symbol(BackEndHelper::usd_to_currency($transaction->discount_amount)),
                'Expense Type' => isset($transaction->coupon->coupon_type) ? ucwords(str_replace('_', ' ', $transaction->coupon->coupon_type)):'',
            );
        }

        return (new FastExcel($tranData))->download('expense_transaction.xlsx');

    }

    /**
     * expense transaction summary pdf
     */
    public function expense_transaction_summary_pdf(Request $request)
    {

        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $search = $request['search'];
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        $duration = str_replace('_', ' ', $date_type);
        if ($date_type == 'custom_date') {
            $duration = 'From ' . $from . ' To ' . $to;
        }

        $expense_transaction_query = Order::with(['order_transaction', 'coupon'])
            ->where(['coupon_discount_bearer'=> 'inhouse', 'order_status'=>'delivered'])
            ->whereNotIn('coupon_code', ['0', 'NULL'])
            ->whereHas('order_transaction', function ($query) use($search){
                $query->where(['status'=>'disburse'])
                    ->when($search, function ($q) use ($search) {
                        $q->where('transaction_id', 'like', "%{$search}%");
                    });
            });
        $expense_transactions = self::date_wise_common_filter($expense_transaction_query, $date_type, $from, $to)->get();
        $total_expense = 0;
        $free_delivery = 0;
        $coupon_discount = 0;
        if($expense_transactions){
            foreach ($expense_transactions as $transaction){
                $total_expense += $transaction->discount_amount;
                if(isset($transaction->coupon->coupon_type) && $transaction->coupon->coupon_type == 'free_delivery'){
                    $free_delivery += $transaction->discount_amount;
                }else{
                    $coupon_discount += $transaction->discount_amount;
                }
            }
        }

        $data = array(
            'total_expense' => $total_expense,
            'free_delivery' => $free_delivery,
            'coupon_discount' => $coupon_discount,
            'company_phone' => $company_phone,
            'company_name' => $company_name,
            'company_email' => $company_email,
            'company_web_logo' => $company_web_logo,
            'duration' => $duration,
        );

        $mpdf_view = View::make('admin-views.transaction.expense_transaction_summary_report_pdf', compact('data'));
        Helpers::gen_mpdf($mpdf_view, 'expense_transaction_summary_report_', $date_type);

    }

    public function pdf_order_wise_expense_transaction(Request $request)
    {
        $company_phone = BusinessSetting::where('type', 'company_phone')->first()->value;
        $company_email = BusinessSetting::where('type', 'company_email')->first()->value;
        $company_name = BusinessSetting::where('type', 'company_name')->first()->value;
        $company_web_logo = BusinessSetting::where('type', 'company_web_logo')->first()->value;

        $transaction = Order::with(['order_transaction', 'coupon'])
            ->where('id', $request->id)->first();

        $mpdf_view = View::make('admin-views.transaction.order_wise_expense_pdf', compact('company_phone', 'company_name', 'company_email', 'company_web_logo', 'transaction'));
        Helpers::gen_mpdf($mpdf_view, 'expense_transaction_', $request->id);

    }

    public function expense_transaction_chart_filter($request)
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

            $this_year = self::expense_transaction_same_year($request, $current_start_year, $current_end_year, $from_year, $number, $default_inc);
            return $this_year;

        } elseif ($date_type == 'this_month') { //this month table
            $current_month_start = date('Y-m-01');
            $current_month_end = date('Y-m-t');
            $inc = 1;
            $month = date('m');
            $number = date('d', strtotime($current_month_end));

            $this_month = self::expense_transaction_same_month($request, $current_month_start, $current_month_end, $month, $number, $inc);
            return $this_month;

        } elseif ($date_type == 'this_week') {
            $this_week = self::expense_transaction_this_week($request);
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
                $different_year = self::expense_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year);
                return $different_year;

            } elseif ($from_month != $to_month) {
                $same_year = self::expense_transaction_same_year($request, $start_date, $end_date, $from_year, $to_month, $from_month);
                return $same_year;

            } elseif ($from_month == $to_month) {
                $same_month = self::expense_transaction_same_month($request, $start_date, $end_date, $from_month, $to_day, $from_day);
                return $same_month;
            }

        }
    }

    public function expense_transaction_same_month($request, $start_date, $end_date, $month_date, $number, $default_inc)
    {
        $year_month = date('Y-m', strtotime($start_date));
        $month = date("F", strtotime("$year_month"));
        $orders = self::expense_chart_common_query($request)
            ->selectRaw('sum(discount_amount) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month, DAY(updated_at) day')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $day = date('jS', strtotime("$year_month-$inc"));
            $discount_amount[$day . '-' . $month] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $inc) {
                    $discount_amount[$day . '-' . $month] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );
    }

    public function expense_transaction_this_week($request)
    {
        $start_date = Carbon::now()->startOfWeek();
        $end_date = Carbon::now()->endOfWeek();

        $number = 6;
        $period = CarbonPeriod::create(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
        $day_name = array();
        foreach ($period as $date) {
            array_push($day_name, $date->format('l'));
        }

        $orders = self::expense_chart_common_query($request)
            ->select(
                DB::raw('sum(discount_amount) as discount_amount'),
                DB::raw("(DATE_FORMAT(updated_at, '%W')) as day")
            )
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%D')"))
            ->latest('updated_at')->get();

        for ($inc = 0; $inc <= $number; $inc++) {
            $discount_amount[$day_name[$inc]] = 0;
            foreach ($orders as $match) {
                if ($match['day'] == $day_name[$inc]) {
                    $discount_amount[$day_name[$inc]] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );
    }

    public function expense_transaction_same_year($request, $start_date, $end_date, $from_year, $number, $default_inc)
    {

        $orders = self::expense_chart_common_query($request)
            ->selectRaw('sum(discount_amount) as discount_amount, YEAR(updated_at) year, MONTH(updated_at) month')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%M')"))
            ->latest('updated_at')->get();

        for ($inc = $default_inc; $inc <= $number; $inc++) {
            $month = date("F", strtotime("2023-$inc-01"));
            $discount_amount[$month . '-' . $from_year] = 0;
            foreach ($orders as $match) {
                if ($match['month'] == $inc) {
                    $discount_amount[$month . '-' . $from_year] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );
    }

    public function expense_transaction_different_year($request, $start_date, $end_date, $from_year, $to_year)
    {
        $orders = self::expense_chart_common_query($request)
            ->selectRaw('sum(discount_amount) as discount_amount, YEAR(updated_at) year')
            ->groupBy(DB::raw("DATE_FORMAT(updated_at, '%Y')"))
            ->latest('updated_at')->get();

        for ($inc = $from_year; $inc <= $to_year; $inc++) {
            $discount_amount[$inc] = 0;
            foreach ($orders as $match) {
                if ($match['year'] == $inc) {
                    $discount_amount[$inc] = $match['discount_amount'];
                }
            }
        }

        return array(
            'discount_amount' => $discount_amount,
        );

    }

    public function expense_chart_common_query($request){
        $from = $request['from'];
        $to = $request['to'];
        $date_type = $request['date_type'] ?? 'this_year';

        $order_query = Order::where(['coupon_discount_bearer'=> 'inhouse', 'order_status'=>'delivered'])
            ->whereNotIn('coupon_code', ['0', 'NULL'])
            ->whereHas('order_transaction', function ($query){
                $query->where(['status'=>'disburse']);
            });

        return self::date_wise_common_filter($order_query, $date_type, $from, $to);
    }

}
