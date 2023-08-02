<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{\App\CPU\translate('invoice')}}</title>
    <meta http-equiv="Content-Type" content="text/html;"/>
    <meta charset="UTF-8">
    <style media="all">
        * {
            margin: 0;
            padding: 0;
            line-height: 1.3;
            font-family: sans-serif;
            color: #333542;
        }

        /* IE 6 */
        * html .footer {
            position: absolute;
            top: expression((0-(footer.offsetHeight)+(document.documentElement.clientHeight ? document.documentElement.clientHeight : document.body.clientHeight)+(ignoreMe = document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop))+'px');
        }

        body {
            font-size: .75rem;
        }

        img {
            max-width: 100%;
        }

        .customers {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }
        table {
            width: 100%;
        }

        table thead th {
            padding: 8px;
            font-size: 11px;
            text-align: left;
        }

        table tbody th,
        table tbody td {
            padding: 8px;
            /*font-size: 11px;*/
        }

        table.fz-12 thead th {
            font-size: 12px;
        }

        table.fz-12 tbody th,
        table.fz-12 tbody td {
            font-size: 12px;
        }

        table.customers thead th {
            background-color: #0177CD;
            color: #fff;
        }

        table.customers tbody th,
        table.customers tbody td {
            background-color: #FAFCFF;
        }

        table.calc-table th {
            text-align: left;
        }

        table.calc-table td {
            text-align: right;
        }
        table.calc-table td.text-left {
            text-align: left;
        }

        .table-total {
            font-family: Arial, Helvetica, sans-serif;
        }


        .text-left {
            text-align: left !important;
        }

        .pb-2 {
            padding-bottom: 8px !important;
        }

        .pb-3 {
            padding-bottom: 16px !important;
        }

        .text-right {
            text-align: right !important;
        }

        table th.text-right {
            text-align: right !important;
        }

        @media print {
            table th.text-right {
                text-align: right !important;
            }
        }

        .content-position {
            padding: 15px 40px;
        }

        .content-position-y {
            padding: 0px 40px;
        }

        .text-white {
            color: white !important;
        }

        .bs-0 {
            border-spacing: 0;
        }
        .text-center {
            text-align: center;
        }
        .mb-1 {
            margin-bottom: 4px !important;
        }
        .mb-2 {
            margin-bottom: 8px !important;
        }
        .mb-4 {
            margin-bottom: 24px !important;
        }
        .mb-30 {
            margin-bottom: 30px !important;
        }
        .px-10 {
            padding-left: 10px;
            padding-right: 10px;
        }
        .fz-14 {
            font-size: 14px;
        }
        .fz-12 {
            font-size: 12px;
        }
        .fz-10 {
            font-size: 10px;
        }
        .font-normal {
            font-weight: 400;
        }
        .font-weight-normal {
            font-weight: normal;
        }
        .border-dashed-top {
            border-top: 1px dashed #ddd;
        }
        .font-weight-bold {
            font-weight: 700;
        }
        .bg-light {
            background-color: #F7F7F7;
        }
        .py-30 {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .py-4 {
            padding-top: 24px;
            padding-bottom: 24px;
        }
        .d-flex {
            display: flex;
        }
        .gap-2 {
            gap: 8px;
        }
        .flex-wrap {
            flex-wrap: wrap;
        }
        .align-items-center {
            align-items: center;
        }
        .justify-content-center {
            justify-content: center;
        }
        a {
            color: rgba(0, 128, 245, 1);
        }
        .p-1 {
            padding: 4px !important;
        }
        .h2 {
            font-size: 1.5em;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

        .h4 {
            margin-block-start: 1.33em;
            margin-block-end: 1.33em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            font-weight: bold;
        }

    </style>
</head>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<body>
@php
    use App\Model\BusinessSetting;
    $company_phone =BusinessSetting::where('type', 'company_phone')->first()->value;
    $company_email =BusinessSetting::where('type', 'company_email')->first()->value;
    $company_name =BusinessSetting::where('type', 'company_name')->first()->value;
    $company_web_logo =BusinessSetting::where('type', 'company_web_logo')->first()->value;
    $company_mobile_logo =BusinessSetting::where('type', 'company_mobile_logo')->first()->value;
@endphp

<div class="first">
    <table class="content-position mb-30">
        <tr>
            <th class="p-0 text-left" style="font-size: 26px">
                {{\App\CPU\translate('Order_Invoice')}}
            </th>
            <th class="p-0 text-right">
                <img height="40" src="{{asset("storage/app/public/company/$company_web_logo")}}" alt="">
            </th>
        </tr>

    </table>

    <table class="bs-0 mb-30 px-10">
        <tr>
            <th class="content-position-y text-left">
                <h4 class="text-uppercase mb-1 fz-14">
                    {{\App\CPU\translate('invoice')}} #{{ $order->id }}
                </h4> <br>
                <h4 class="text-uppercase mb-1 fz-14">
                    {{\App\CPU\translate('Shop_Name')}}
                    : {{ $order->seller_is == 'admin' ? $company_name : (isset($order->seller->shop) ? $order->seller->shop->name : \App\CPU\translate('not_found')) }}
                </h4>
                @if($order['seller_is']!='admin' && isset($order['seller']->gst) != null)
                    <h4 class="text-capitalize fz-12">{{\App\CPU\translate('GST')}}
                        : {{ $order['seller']->gst }}</h4>
                @endif
            </th>
            <th class="content-position-y text-right">
                <h4 class="fz-14">{{\App\CPU\translate('date')}} : {{date('d-m-Y h:i:s a',strtotime($order['created_at']))}}</h4>
            </th>
        </tr>
    </table>
</div>
@if ($order->order_type == 'default_type')
    <div class="">
        <section>
            <table class="content-position-y fz-12">
                <tr>
                    <td class="font-weight-bold p-1">
                        <table>
                            <tr>
                                <td>
                                    @if ($order->shippingAddress)
                                        <span class="h2" style="margin: 0px;">{{\App\CPU\translate('shipping_to')}} </span>
                                        <div class="h4 montserrat-normal-600">
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['f_name'].' '.$order->customer['l_name']:\App\CPU\translate('name_not_found')}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['email']:\App\CPU\translate('email_not_found')}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['phone']:\App\CPU\translate('phone_not_found')}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->shippingAddress ? $order->shippingAddress['address'] : ""}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->shippingAddress ? $order->shippingAddress['city'] : ""}} {{$order->shippingAddress ? $order->shippingAddress['zip'] : ""}}</p>
                                        </div>
                                    @else
                                        <span class="h2" style="margin: 0px;">{{\App\CPU\translate('customer_info')}} </span>
                                        <div class="h4 montserrat-normal-600">
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['f_name'].' '.$order->customer['l_name']:\App\CPU\translate('name_not_found')}}</p>
                                            @if (isset($order->customer) && $order->customer['id']!=0)
                                                <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['email']:\App\CPU\translate('email_not_found')}}</p>
                                                <p style=" margin-top: 6px; margin-bottom:0px;">{{$order->customer !=null? $order->customer['phone']:\App\CPU\translate('phone_not_found')}}</p>
                                            @endif
                                        </div>
                                        @endif
                                        </p>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        <table>
                            <tr>
                                <td class="text-right">
                                    @if ($order->billingAddress)
                                        <span class="h2" >{{\App\CPU\translate('billing_address')}} </span>
                                        <div class="h4 montserrat-normal-600">
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['contact_person_name'] : ""}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['phone'] : ""}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['address'] : ""}}</p>
                                            <p class="font-weight-normal" style=" margin-top: 6px; margin-bottom:0px;">{{$order->billingAddress ? $order->billingAddress['city'] : ""}} {{$order->billingAddress ? $order->billingAddress['zip'] : ""}}</p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </section>
    </div>
@else
    <div class="row">
        <section>
            <table class="content-position-y" style="width: 100%">
                <tr>
                    <td class="text-center" valign="top">
                        <span class="h2" style="margin: 0px;">{{\App\CPU\translate('POS_order')}} </span>

                    </td>

                </tr>
            </table>
        </section>
    </div>
@endif

<br>

<div>
    <div class="content-position-y">
        <table class="customers bs-0">
            <thead>
            <tr>
                <th>{{\App\CPU\translate('no.')}}</th>
                <th>{{\App\CPU\translate('item_description')}}</th>
                <th>
                    {{\App\CPU\translate('unit_price')}}
                </th>
                <th>
                    {{\App\CPU\translate('qty')}}
                </th>
                <th class="text-right">
                    {{\App\CPU\translate('total')}}
                </th>
            </tr>
            </thead>
            @php
                $subtotal=0;
                $total=0;
                $sub_total=0;
                $total_tax=0;
                $total_shipping_cost=0;
                $total_discount_on_product=0;
                $extra_discount=0;
            @endphp
            <tbody>
            @foreach($order->details as $key=>$details)
                @php $subtotal=($details['price'])*$details->qty @endphp
                <tr>
                    <td>{{$key+1}}</td>
                    <td>
                        {{$details['product']?$details['product']->name:''}}
                        @if($details['variant'])
                            <br>
                            {{\App\CPU\translate('variation')}} : {{$details['variant']}}
                        @endif
                    </td>
                    <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($details['price']))}}</td>
                    <td>{{$details->qty}}</td>
                    <td class="text-right">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                </tr>

                @php
                    $sub_total+=$details['price']*$details['qty'];
                    $total_tax+=$details['tax'];
                    $total_shipping_cost+=$details->shipping ? $details->shipping->cost :0;
                    $total_discount_on_product+=$details['discount'];
                    $total+=$subtotal;
                @endphp
            @endforeach
            </tbody>
        </table>
    </div>
</div>
<?php
if ($order['extra_discount_type'] == 'percent') {
    $extra_discount = ($sub_total / 100) * $order['extra_discount'];
} else {
    $extra_discount = $order['extra_discount'];
}

?>
@php($shipping=$order['shipping_cost'])
<div class="content-position-y">
    <table class="fz-12">
        <tr>
            <th class="text-left" style="width: 60%">
                <h4 class="fz-12 mb-1">{{\App\CPU\translate('payment_details')}}</h4>
                <h5 class="fz-12 mb-1 font-weight-normal">{{ str_replace('_',' ',$order->payment_method) }}</h5>
                <p class="fz-12 font-weight-normal">{{$order->payment_status}}
                    , {{date('y-m-d',strtotime($order['created_at']))}}</p>

                @if ($order->delivery_type !=null)
                    <h4 class="fz-12 mb-1">{{\App\CPU\translate('delivery_info')}} </h4>
                    @if ($order->delivery_type == 'self_delivery')
                        <p class="fz-12 font-normal">
                            <span class="font-weight-normal">
                                {{\App\CPU\translate('self_delivery')}}
                            </span>
                            <br>
                            <span class="font-weight-normal">
                                {{\App\CPU\translate('delivery_man_name')}} : {{$order->delivery_man['f_name'].' '.$order->delivery_man['l_name']}}
                            </span>
                            <br>
                            <span class="font-weight-normal">
                                {{\App\CPU\translate('delivery_man_phone')}} : {{$order->delivery_man['phone']}}
                            </span>
                        </p>
                    @else
                        <p>
                        <span class="font-weight-normal">
                            {{$order->delivery_service_name}}
                        </span>
                            <br>
                            <span class="font-weight-normal">
                            {{\App\CPU\translate('tracking_id')}} : {{$order->third_party_delivery_tracking_id}}
                        </span>
                        </p>
                    @endif
                @endif
            </th>

            <th class="calc-table">
                <table>
                    <tbody>

                    <tr>
                        <td class="p-1 text-left"><b>{{\App\CPU\translate('sub_total')}}</b></td>
                        <td class="p-1 text-right">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($sub_total))}}</td>

                    </tr>
                    <tr>
                        <td class="p-1 text-left"><b>{{\App\CPU\translate('tax')}}</b></td>
                        <td class="p-1 text-right">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_tax))}}</td>
                    </tr>
                    @if($order->order_type == 'default_type')
                        <tr>
                            <td class="p-1 text-left"><b>{{\App\CPU\translate('shipping')}}</b></td>
                            <td class="p-1 text-right">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="p-1 text-left"><b>{{\App\CPU\translate('coupon_discount')}}</b></td>
                        <td class="p-1 text-right">
                            - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->discount_amount))}}</td>
                    </tr>
                    <tr>
                        <td class="p-1 text-left"><b>{{\App\CPU\translate('discount_on_product')}}</b></td>
                        <td class="p-1 text-right">
                            - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_discount_on_product))}}</td>
                    </tr>
                    @if ($order->order_type != 'default_type')
                        <tr>
                            <td class="p-1 text-left"><b>{{\App\CPU\translate('extra_discount')}}</b></td>
                            <td class="p-1 text-right">
                                - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($extra_discount))}}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="border-dashed-top font-weight-bold text-left"><b>{{\App\CPU\translate('total')}}</b></td>
                        <td class="border-dashed-top font-weight-bold text-right">
                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->order_amount))}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </th>
        </tr>
    </table>
</div>
<br>
<br><br><br>

<div class="row">
    <section>
        <table>
            <tr>
                <th class="content-position-y bg-light py-4">
                    <div class="d-flex justify-content-center gap-2">
                        <div class="mb-2">
                            <i class="fa fa-phone"></i>
                            {{\App\CPU\translate('phone')}}
                            : {{\App\Model\BusinessSetting::where('type','company_phone')->first()->value}}
                        </div>
                        <div class="mb-2">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                            {{\App\CPU\translate('email')}}
                            : {{$company_email}}
                        </div>
                    </div>
                    <div class="mb-2">
                        <i class="fa fa-globe" aria-hidden="true"></i>
                        {{\App\CPU\translate('website')}}
                        : {{url('/')}}
                    </div>
                    <div>
                        {{\App\CPU\translate('All_copy_right_reserved_©_'.date('Y').'_').$company_name}}
                    </div>
                </th>
            </tr>
        </table>
    </section>
</div>

</body>
</html>
