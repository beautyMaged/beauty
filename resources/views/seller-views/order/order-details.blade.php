@extends('layouts.back-end.app-seller')

@section('title',\App\CPU\translate('Order Details'))

@push('css_or_js')
@endpush

@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/all-orders.png')}}" alt="">
                {{\App\CPU\translate('order_details')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row gy-3" id="printableArea">
            <div class="col-lg-8 col-xxl-9">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Body -->
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-10 justify-content-between mb-4">
                            <div class="d-flex flex-column gap-10">
                                <h4 class="text-capitalize">{{\App\CPU\translate('Order_ID')}} #{{$order['id']}}</h4>
                                <div class="d-flex gap-2">
                                    <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                                </div>
                            </div>
                            <div class="text-sm-right">
                                <div class="d-flex flex-wrap gap-10">
                                    <div class="">
                                        @if (isset($shipping_address['latitude']) && isset($shipping_address['longitude']))
                                            <button class="btn btn--primary px-4" data-toggle="modal" data-target="#locationModal"><i
                                                    class="tio-map"></i> {{\App\CPU\translate('show_locations_on_map')}}</button>
                                        @else
                                            <button class="btn btn-warning px-4"><i
                                                    class="tio-map"></i> {{\App\CPU\translate('shipping_address_has_been_given_below')}}
                                            </button>
                                        @endif
                                    </div>
                                    <a class="btn btn--primary px-4" target="_blank"
                                    href="{{route('seller.orders.generate-invoice',[$order['id']])}}">
                                        <i class="tio-print mr-1"></i> {{\App\CPU\translate('Print')}} {{\App\CPU\translate('invoice')}}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <!-- Order status -->
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{\App\CPU\translate('Status')}}: </span>

                                        @if($order['order_status']=='pending')
                                        <span class="badge badge-soft-info font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ',$order['order_status'])}}</span>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ','Failed To Deliver')}}</span>
                                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                            <span class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ',$order['order_status'] == 'processing' ? 'Packaging' : $order['order_status'])}}</span>

                                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                                            <span class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ',$order['order_status'])}}</span>
                                        @else
                                            <span class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">{{str_replace('_',' ',$order['order_status'])}}</span>
                                        @endif
                                    </div>

                                    <!-- Payment Method -->
                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize">
                                        <span class="title-color">{{\App\CPU\translate('Payment')}} {{\App\CPU\translate('Method')}} :</span>
                                        <strong>{{\App\CPU\translate(str_replace('_',' ',$order['payment_method']))}}</strong>
                                    </div>

                                    <!-- reference-code -->
                                    @if($order->payment_method != 'cash_on_delivery' && $order->payment_method != 'pay_by_wallet')
                                        <div class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize">
                                            <span class="title-color">{{\App\CPU\translate('Reference')}} {{\App\CPU\translate('Code')}} :</span>
                                            <strong>{{str_replace('_',' ',$order['transaction_ref'])}} {{ $order->payment_method == 'offline_payment' ? '('.$order->payment_by.')':'' }}</strong>
                                        </div>
                                    @endif

                                    <!-- Payment Status -->
                                    <div class="payment-status d-flex justify-content-sm-end gap-10">
                                        <span class="title-color">{{\App\CPU\translate('Payment_Status')}}:</span>
                                        @if($order['payment_status']=='paid')
                                            <span class="text-success font-weight-bold text-capitalize">{{\App\CPU\translate('Paid')}}</span>
                                        @else
                                            <span class="text-danger font-weight-bold text-capitalize">{{\App\CPU\translate('Unpaid')}}</span>
                                        @endif
                                    </div>

                                    @if($order->payment_method == 'offline_payment')
                                        <div class="col-md-12 payment-status d-flex justify-content-sm-end gap-10">
                                            <span class="title-color">{{\App\CPU\translate('Payment_Note')}}:</span>
                                            <span>
                                                {{ $order->payment_note }}
                                            </span>
                                        </div>
                                    @endif

                                    @if(\App\CPU\Helpers::get_business_settings('order_verification'))
                                        <span class="">
                                            {{\App\CPU\translate('order_verification_code')}} : <strong>{{$order['verification_code']}}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <!-- Order Note -->
                        <div class="mb-5">
                            @if ($order->order_note !=null)
                                <span class="font-weight-bold text-capitalize">
                                    {{\App\CPU\translate('order_note')}} :
                                </span>
                                <p class="pl-1">
                                    {{$order->order_note}}
                                </p>
                            @endif
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table class="table fz-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th>{{\App\CPU\translate('SL')}}</th>
                                        <th>{{\App\CPU\translate('Item Details')}}</th>
                                        <th>{{\App\CPU\translate('Variations')}}</th>
                                        <th>{{\App\CPU\translate('Tax')}}</th>
                                        <th>{{\App\CPU\translate('Discount')}}</th>
                                        <th>{{\App\CPU\translate('Price')}}</th>
                                    </tr>
                                </thead>

                                <tbody>
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($tax=0)
                                @php($row=0)
                                @foreach($order->details as $key=>$detail)
                                    @if($detail->product)
                                        <tr>
                                            <td>{{ ++$row }}</td>
                                            <td>
                                                <div class="media align-items-center gap-10">
                                                    <img class="avatar avatar-60 rounded"
                                                         onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                                         src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$detail->product['thumbnail']}}"
                                                         alt="Image Description">
                                                    <div>
                                                        <h6 class="title-color">{{substr($detail->product['name'],0,30)}}{{strlen($detail->product['name'])>10?'...':''}}</h6>
                                                        <div><strong>{{\App\CPU\translate('price')}} :</strong> {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']))}}</div>
                                                        <div><strong>{{\App\CPU\translate('qty')}} :</strong> {{$detail->qty}}</div>
                                                    </div>
                                                </div>
                                                @if($detail->product->digital_product_type == 'ready_after_sell')
                                                    <button type="button" class="btn btn-sm btn--primary mt-2" title="File Upload" data-toggle="modal" data-target="#fileUploadModal-{{ $detail->id }}" onclick="modalFocus('fileUploadModal-{{ $detail->id }}')">
                                                        <i class="tio-file-outlined"></i> {{\App\CPU\translate('File')}}
                                                    </button>
                                                @endif
                                            </td>
                                            <td>{{$detail['variant']}}</td>
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['tax']))}}</td>
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['discount']))}}</td>
                                            @php($subtotal=$detail['price']*$detail->qty+$detail['tax']-$detail['discount'])
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</td>
                                        </tr>

                                        @if(isset($detail->product->digital_product_type) && $detail->product->digital_product_type == 'ready_after_sell')
                                            <div class="modal fade" id="fileUploadModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <form action="{{ route('seller.orders.digital-file-upload-after-sell') }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                @if($detail->digital_file_after_sell)
                                                                    <div class="mb-4">
                                                                        {{\App\CPU\translate('uploaded_file')}} :
                                                                        <a href="{{ asset('storage/app/public/product/digital-product/'.$detail->digital_file_after_sell) }}"
                                                                           class="btn btn-success btn-sm" title="Download" download><i class="tio-download"></i> {{\App\CPU\translate('Download')}}</a>
                                                                    </div>
                                                                @endif
                                                                <input type="file" name="digital_file_after_sell" class="form-control">
                                                                    <div class="mt-1 text-info">{{\App\CPU\translate('File type: jpg, jpeg, png, gif, zip, pdf')}}</div>
                                                                <input type="hidden" value="{{ $detail->id }}" name="order_id">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('Close')}}</button>
                                                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Upload')}}</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @php($discount+=$detail['discount'])
                                        @php($tax+=$detail['tax'])
                                        @php($shipping+=$detail->shipping ? $detail->shipping->cost :0)

                                        @php($total+=$subtotal)
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr />

                        @php($shipping=$order['shipping_cost'])
                        @php($coupon_discount=$order['discount_amount'])
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row gy-1 text-sm-right">
                                    <dt class="col-sm-6 font-weight-normal">{{\App\CPU\translate('Shipping')}}</dt>
                                    <dd class="col-sm-6 font-weight-normal title-color">
                                        {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}
                                    </dd>

                                    <dt class="col-sm-6 font-weight-normal">{{\App\CPU\translate('coupon_discount')}}</dt>
                                    <dd class="col-sm-6 font-weight-normal title-color">
                                        - {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}
                                    </dd>

                                    @if($order['coupon_discount_bearer'] == 'inhouse' && !in_array($order['coupon_code'], [0, NULL]))
                                        <dt class="col-sm-6 font-weight-normal">{{\App\CPU\translate('coupon_discount')}} ({{\App\CPU\translate('admin_bearer')}})</dt>
                                        <dd class="col-sm-6 font-weight-normal title-color">
                                            + {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}
                                        </dd>
                                        @php($total += $coupon_discount)
                                    @endif
                                    <dt class="col-sm-6">{{\App\CPU\translate('Total')}}</dt>
                                    <dd class="col-sm-6 title-color">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total+$shipping-$coupon_discount))}}</strong>
                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>

                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
            <div class="col-lg-4 col-xxl-3 d-flex flex-column gap-3">
                <div class="card">
                    <div class="card-body text-capitalize d-flex flex-column gap-4">
                        <h4 class="mb-0 text-center">{{\App\CPU\translate('Order_&_Shipping_Info')}}</h4>

                        <div>
                            <label class="font-weight-bold title-color fz-14">{{\App\CPU\translate('Order Status')}}</label>
                            <select name="order_status" onchange="order_status(this.value)" class="status form-control" data-id="{{$order['id']}}">
                                <option
                                    value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{\App\CPU\translate('Pending')}}</option>
                                <option
                                    value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{\App\CPU\translate('Confirmed')}}</option>
                                <option
                                    value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{\App\CPU\translate('Packaging')}} </option>

                                @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
                                @if( $shippingMethod=='sellerwise_shipping')
                                    <option
                                        value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{\App\CPU\translate('out_for_delivery')}} </option>
                                    <option
                                        value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{\App\CPU\translate('Delivered')}} </option>
                                    <option
                                        value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{\App\CPU\translate('Returned')}}</option>
                                    <option
                                        value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{\App\CPU\translate('Failed_to_deliver')}} </option>
                                    <option
                                        value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{\App\CPU\translate('Canceled')}} </option>
                                @endif
                            </select>
                        </div>

                        @if(($order['payment_method']=='cash_on_delivery' || $order['payment_method']=='offline_payment') && $shipping_method=='sellerwise_shipping')
                            <div>
                                <label class="font-weight-bold title-color fz-14">{{\App\CPU\translate('Payment Status')}}</label>
                                <select name="payment_status" class="payment_status form-control"
                                        data-id="{{$order['id']}}">
                                    <option
                                        onclick="route_alert('{{route('seller.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}','{{\App\CPU\translate('Change status to paid')}} ?')"
                                        href="javascript:"
                                        value="paid" {{$order->payment_status == 'paid'?'selected':''}} >
                                        {{\App\CPU\translate('Paid')}}
                                    </option>
                                    <option
                                        value="unpaid" {{$order->payment_status == 'unpaid'?'selected':''}} >
                                        {{\App\CPU\translate('Unpaid')}}
                                    </option>
                                </select>
                            </div>
                        @endif

                        @if($physical_product)
                        <ul class="list-unstyled">
                            <li>
                                <label class="font-weight-bold title-color fz-14">
                                    {{\App\CPU\translate('shipping_type')}}
                                    : {{str_replace('_',' ',$order->shipping_type)}}
                                </label>
                            </li>
                            @if ($order->shipping_type == 'order_wise')
                            <li>
                                <label class="font-weight-bold title-color fz-14">
                                    {{\App\CPU\translate('shipping')}} {{\App\CPU\translate('method')}}
                                    : {{$order->shipping ? $order->shipping->title :'No shipping method selected'}}
                                </label>
                            </li>
                            @endif
                            @if ($shipping_method=='sellerwise_shipping')
                            <li>
                                <select class="form-control text-capitalize" name="delivery_type" onchange="choose_delivery_type(this.value)">
                                    <option value="0">
                                        {{\App\CPU\translate('choose_delivery_type')}}
                                    </option>

                                    <option value="self_delivery" {{$order->delivery_type=='self_delivery'?'selected':''}}>
                                        {{\App\CPU\translate('by_self_delivery_man')}}
                                    </option>
                                    <option value="third_party_delivery" {{$order->delivery_type=='third_party_delivery'?'selected':''}} >
                                        {{\App\CPU\translate('by_third_party_delivery_service')}}
                                    </option>
                                </select>
                            </li>
                            <li id="choose_delivery_man" class="mt-3 choose_delivery_man">
                                <label for="" class="font-weight-bold title-color fz-14">
                                    {{\App\CPU\translate('choose_delivery_man')}} ({{ session('currency_symbol') }})
                                </label>
                                <select class="form-control text-capitalize js-select2-custom" name="delivery_man_id" onchange="addDeliveryMan(this.value)">
                                    <option
                                        value="0">{{\App\CPU\translate('select')}}</option>
                                    @foreach($delivery_men as $deliveryMan)
                                        <option
                                            value="{{$deliveryMan['id']}}" {{$order['delivery_man_id']==$deliveryMan['id']?'selected':''}}>
                                            {{$deliveryMan['f_name'].' '.$deliveryMan['l_name'].' ('.$deliveryMan['phone'].' )'}}
                                        </option>
                                    @endforeach
                                </select>
                            </li>
                            <li class="choose_delivery_man mt-3">
                                <label class="font-weight-bold title-color fz-14">
                                    {{\App\CPU\translate('deliveryman_will_get')}}
                                </label>
                                <input type="number" id="deliveryman_charge" onkeyup="amountDateUpdate(this, event)" value="{{ $order->deliveryman_charge }}" name="deliveryman_charge" class="form-control" placeholder="Ex: 20" required>
                            </li>
                            <li class="choose_delivery_man mt-3">
                                <label class="font-weight-bold title-color fz-14">
                                    {{\App\CPU\translate('expected_delivery_date')}}
                                </label>
                                <input type="date" onchange="amountDateUpdate(this, event)" value="{{ $order->expected_delivery_date }}" name="expected_delivery_date" id="expected_delivery_date" class="form-control" required>
                            </li>

                            @endif
                            <li class=" mt-3" id="by_third_party_delivery_service_info">
                                <span>
                                    {{\App\CPU\translate('delivery_service_name')}} : {{$order->delivery_service_name}}
                                </span>
                                <span class="float-right">
                                    <a href="javascript:" onclick="choose_delivery_type('third_party_delivery')">
                                        <i class="tio-edit"></i>
                                    </a>
                                </span>
                                <br>
                                <span>
                                    {{\App\CPU\translate('tracking_id')}} : {{$order->third_party_delivery_tracking_id}}
                                </span>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>

                <!-- Card -->
                <div class="card">
                    <!-- Body -->
                    @if($order->customer)
                        <div class="card-body">
                            <h4 class="mb-4 d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                {{\App\CPU\translate('Customer_information')}}
                            </h4>
                            <div class="media">
                                <div class="mr-3">
                                    <img class="avatar rounded-circle avatar-70"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/app/public/profile/'.$order->customer->image)}}"
                                        alt="Image">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span class="title-color"><strong>{{$order->customer['f_name'].' '.$order->customer['l_name']}}</strong></span>
                                    <span class="title-color">
                                        <strong>{{\App\Model\Order::where('customer_id',$order['customer_id'])->count()}} </strong>
                                        {{\App\CPU\translate('orders')}}
                                    </span>
                                    <span class="title-color break-all"><strong>{{$order->customer['phone']}}</strong></span>
                                    <span class="title-color break-all">{{$order->customer['email']}}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media">
                                <span>{{\App\CPU\translate('no_customer_found')}}</span>
                            </div>
                        </div>
                    @endif
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Card -->
                @if($physical_product)
                <div class="card">
                    <!-- Body -->
                    @if($order->customer)
                        <div class="card-body">
                            <h4 class="mb-4 d-flex align-items-center gap-2">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                {{\App\CPU\translate('shipping_address')}}
                            </h4>

                            @if($order->shippingAddress)
                                @php($shipping_address=$order->shippingAddress)
                            @else
                                @php($shipping_address=json_decode($order['shipping_address_data']))
                            @endif

                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span>{{\App\CPU\translate('Name')}} :</span>
                                    <strong>{{$shipping_address? $shipping_address->contact_person_name : ''}}</strong>
                                </div>
                                <div>
                                    <span>{{\App\CPU\translate('Contact')}}:</span>
                                    <strong>{{$shipping_address ? $shipping_address->phone  : ''}}</strong>
                                </div>
                                <div>
                                    <span>{{\App\CPU\translate('City')}}:</span>
                                    <strong>{{$shipping_address ? $shipping_address->city : ''}}</strong>
                                </div>
                                <div>
                                    <span>{{\App\CPU\translate('zip_code')}} :</span>
                                    <strong>{{$shipping_address ? $shipping_address->zip  : ''}}</strong>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <!-- <span>{{\App\CPU\translate('address')}} :</span> -->
                                    <img src="{{asset('/public/assets/back-end/img/location.png')}}" alt="">
                                    {{$shipping_address ? $shipping_address->address  : ''}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{\App\CPU\translate('no_customer_found')}}</span>
                            </div>
                        </div>
                    @endif
                    <!-- End Body -->
                </div>
                @endif
                <!-- End Card -->

                <!-- Card -->
                <div class="card">
                    <!-- Body -->
                    @if($order->customer)
                        <div class="card-body">
                            <h4 class="mb-4 d-flex gap-2">
                                <img src="{{asset('/public/assets/back-end/img/seller-information.png')}}" alt="">
                                {{\App\CPU\translate('billing_address')}}
                            </h4>

                            @if($order->billingAddress)
                                @php($billing=$order->billingAddress)
                            @else
                                @php($billing=json_decode($order['billing_address_data']))
                            @endif

                            <div class="d-flex flex-column gap-2">
                                <div>
                                    <span>{{\App\CPU\translate('Name')}} :</span>
                                    <strong>{{$billing? $billing->contact_person_name : ''}}</strong>
                                </div>
                                <div>
                                    <span>{{\App\CPU\translate('Contact')}}:</span>
                                    <strong>{{$billing ? $billing->phone  : ''}}</strong>
                                </div>
                                <div>
                                    <span>{{\App\CPU\translate('City')}}:</span>
                                    <strong>{{$billing ? $billing->city : ''}}</strong>
                                </div>
                                <div>
                                    <span>{{\App\CPU\translate('zip_code')}} :</span>
                                    <strong>{{$billing ? $billing->zip  : ''}}</strong>
                                </div>
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{asset('/public/assets/back-end/img/location.png')}}" alt="">
                                    {{$billing ? $billing->address  : ''}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{\App\CPU\translate('no_customer_found')}}</span>
                            </div>
                        </div>
                    @endif
                    <!-- End Body -->
                </div>
                <!-- End Card -->

                <!-- Card -->
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-4 d-flex gap-2">
                            <img src="{{asset('/public/assets/back-end/img/shop-information.png')}}" alt="">
                            {{\App\CPU\translate('Shop_Information')}}
                        </h4>


                        <div class="media">
                            <div class="mr-3">
                                <img class="avatar rounded avatar-70" onerror="this.src='https://6valley.6amtech.com/public/assets/front-end/img/image-place-holder.png'"
                                     src="{{!empty($order->seller->shop) ? asset('storage/app/public/seller/'.auth('seller')->user()->image) : ''}}"
                                     onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'" alt="">
                            </div>
                            @if(!empty($order->seller->shop))
                            <div class="media-body d-flex flex-column gap-2">
                                <h5>{{ $order->seller->shop->name }}</h5>
                                <span class="title-color"><strong>{{ $total_delivered }}</strong> {{\App\CPU\translate('Orders Served')}}</span>
                                <span class="title-color"> <strong>{{ $order->seller->shop->contact }}</strong></span>
                                <div class="d-flex align-items-start gap-2">
                                    <img src="{{asset('/public/assets/back-end/img/location.png')}}" class="mt-1" alt="">
                                    {{ $order->seller->shop->address }}
                                </div>
                            </div>
                            @else
                                <div class="card-body">
                                    <div class="media align-items-center">
                                        <span>{{\App\CPU\translate('no_data_found')}}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
    <!--Show locations on map Modal -->
    <div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="locationModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"
                        id="locationModalLabel">{{\App\CPU\translate('location')}} {{\App\CPU\translate('data')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 modal_body_map">
                            <div class="location-map" id="location-map">
                                <div class="w-100 h-400" id="location_map_canvas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
    <!--Show delivery info Modal -->
    <div class="modal" id="shipping_chose" role="dialog" tabindex="-1" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('update_third_party_delivery_info')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form action="{{route('seller.orders.update-deliver-info')}}" method="POST">
                                @csrf
                                <input type="hidden" name="order_id" value="{{$order['id']}}">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('delivery_service_name')}}</label>
                                        <input class="form-control" type="text" name="delivery_service_name" id="" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{\App\CPU\translate('tracking_id')}} ({{\App\CPU\translate('optional')}})</label>
                                        <input class="form-control" type="text" name="third_party_delivery_tracking_id" id="">
                                    </div>
                                    <button class="btn btn--primary" type="submit">{{\App\CPU\translate('submit')}}</button>
                                </div>
                            </form>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
@endsection
@push('script')
    <script>
        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this?')}}',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {
                            if(data.customer_status==0)
                            {
                                toastr.warning('{{\App\CPU\translate('Account has been deleted, you can not change the status!')}}!');
                                // location.reload();
                            }else {
                                toastr.success('{{\App\CPU\translate('Status Change successfully')}}');
                                // location.reload();
                            }
                        }
                    });
                }
            })
        });

        function order_status(status) {
            var value = status;
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this?')}}',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it!')}}'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": value
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it !!')}}');
                                // location.reload();
                            } else {
                                if(data.payment_status == 0){
                                    toastr.warning('{{\App\CPU\translate('Before delivered you need to make payment status paid!')}}!');
                                    // location.reload();
                                }else if(data.customer_status==0)
                                {
                                    toastr.warning('{{\App\CPU\translate('Account has been deleted, you can not change the status!')}}!');
                                    // location.reload();
                                }else{
                                    toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                    // location.reload();
                                }
                            }
                        }
                    });
                }
            })
        }
    </script>
<script>
    $( document ).ready(function() {
        let delivery_type = '{{$order->delivery_type}}';

        if(delivery_type === 'self_delivery'){
            $('.choose_delivery_man').show();
            $('#by_third_party_delivery_service_info').hide();
        }else if(delivery_type === 'third_party_delivery')
        {
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').show();
        }else{
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').hide();
        }
    });
</script>
<script>
    function choose_delivery_type(val)
    {

        if(val==='self_delivery')
        {
            $('.choose_delivery_man').show();
            $('#by_third_party_delivery_service_info').hide();
        }else if(val==='third_party_delivery'){
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').show();
            $('#shipping_chose').modal("show");
        }else{
            $('.choose_delivery_man').hide();
            $('#by_third_party_delivery_service_info').hide();
        }

    }
</script>
    <script>
        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/seller/orders/add-delivery-man/{{$order['id']}}/' + id,
                data: {
                    'order_id': '{{$order['id']}}',
                    'delivery_man_id': id
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('Delivery man successfully assigned/changed', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('Deliveryman man can not assign/change in that status', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('Add valid data', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function last_location_view() {
            toastr.warning('Only available when order is out for delivery!', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function waiting_for_location() {
            toastr.warning('{{\App\CPU\translate('waiting_for_location')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        function amountDateUpdate(t, e){
            let field_name = $(t).attr('name');
            let field_val = $(t).val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('seller.orders.amount-date-update')}}",
                method: 'POST',
                data: {
                    'order_id': '{{$order['id']}}',
                    'field_name': field_name,
                    'field_val': field_val
                },
                success: function (data) {
                    if (data.status == true) {
                        toastr.success('Deliveryman charge add successfully', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    } else {
                        toastr.error('Failed to add deliveryman charge', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
                error: function () {
                    toastr.error('Add valid data', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }
    </script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{\App\CPU\Helpers::get_business_settings('map_api_key')}}&v=3.49"></script>
    <script>

        function initializegLocationMap() {
            var map = null;
            var myLatlng = new google.maps.LatLng({{$shipping_address->latitude ?? null}}, {{$shipping_address->longitude ?? null}});
            var dmbounds = new google.maps.LatLngBounds(null);
            var locationbounds = new google.maps.LatLngBounds(null);
            var dmMarkers = [];
            dmbounds.extend(myLatlng);
            locationbounds.extend(myLatlng);

            var myOptions = {
                center: myLatlng,
                zoom: 13,
                mapTypeId: google.maps.MapTypeId.ROADMAP,

                panControl: true,
                mapTypeControl: false,
                panControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                zoomControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.LARGE,
                    position: google.maps.ControlPosition.RIGHT_CENTER
                },
                scaleControl: false,
                streetViewControl: false,
                streetViewControlOptions: {
                    position: google.maps.ControlPosition.RIGHT_CENTER
                }
            };
            map = new google.maps.Map(document.getElementById("location_map_canvas"), myOptions);
            console.log(map);
            var infowindow = new google.maps.InfoWindow();

            @if($shipping_address && isset($shipping_address))
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng({{$shipping_address->latitude}}, {{$shipping_address->longitude}}),
                map: map,
                title: "{{$order->customer['f_name']??""}} {{$order->customer['l_name']??""}}",
                icon: "{{asset('public/assets/front-end/img/customer_location.png')}}"
            });

            google.maps.event.addListener(marker, 'click', (function (marker) {
                return function () {
                    infowindow.setContent("<div style='float:left'><img style='max-height:40px;wide:auto;' src='{{asset('storage/app/public/profile/')}}{{$order->customer->image??""}}'></div><div style='float:right; padding: 10px;'><b>{{$order->customer->f_name??""}} {{$order->customer->l_name??""}}</b><br/>{{$shipping_address->address}}</div>");
                    infowindow.open(map, marker);
                }
            })(marker));
            locationbounds.extend(marker.getPosition());
            @endif

            google.maps.event.addListenerOnce(map, 'idle', function () {
                map.fitBounds(locationbounds);
            });
        }

        // Re-init map before show modal
        $('#locationModal').on('shown.bs.modal', function (event) {

            initializegLocationMap();
        });
    </script>
@endpush
