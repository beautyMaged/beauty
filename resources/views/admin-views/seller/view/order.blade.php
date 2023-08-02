@extends('layouts.back-end.app')

@section('title',$seller->shop ? $seller->shop->name : \App\CPU\translate("shop name not found"))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/coupon_setup.png')}}" alt="">
                {{\App\CPU\translate('Seller_Details')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Heading -->
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                <a href="{{route('admin.sellers.seller-list')}}"
                   class="btn btn--primary mt-3 mb-3">{{\App\CPU\translate('Back_to_seller_list')}}</a>
            </div>
            <div>
                @if ($seller->status=="pending")
                    <div class="mt-4 pr-2 float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                        <div class="flex-start">
                            <div class="mx-1"><h4><i class="tio-shop-outlined"></i></h4></div>
                            <div>{{\App\CPU\translate('Seller_request_for_open_a_shop.')}}</div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit"
                                        class="btn btn--primary btn-sm">{{\App\CPU\translate('Approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit"
                                        class="btn btn-danger btn-sm">{{\App\CPU\translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">{{ $seller->shop ? $seller->shop->name : "Shop Name : Update Please" }}</h1>
                </div>
            </div>

            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link "
                           href="{{ route('admin.sellers.view',$seller->id) }}">{{\App\CPU\translate('Shop')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{\App\CPU\translate('Order')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{\App\CPU\translate('Product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'setting']) }}">{{\App\CPU\translate('Setting')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'transaction']) }}">{{\App\CPU\translate('Transaction')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'review']) }}">{{\App\CPU\translate('Review')}}</a>
                    </li>

                </ul>
                <!-- End Nav -->
            </div>
            <!-- End Nav Scroller -->
        </div>
        <!-- End Page Header -->

        <!-- Page Heading -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="order">
                <div class="row pt-2">
                    <div class="col-md-12">
                        <div class="card w-100">
                            <div class="card-header">
                                <h5 class="mb-0">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('info')}}</h5>
                            </div>
                            <!-- Card -->
                            @php($pending_order = App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$seller->id])->where('order_status','pending')->where('order_type','default_type')->get())
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="order-stats order-stats_pending">
                                            <div class="order-stats__content"
                                                 style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                <i class="tio-airdrop"></i>
                                                <h6 class="order-stats__subtitle">{{\App\CPU\translate('pending')}}</h6>
                                            </div>
                                            <div class="order-stats__title">
                                                {{ $pending_order->count() }}
                                            </div>
                                        </div>
                                    </div>
                                    @php($delivered_order = App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$seller->id])->where('order_status','delivered')->where('order_type','default_type')->get())
                                    <div class="col-md-4 mb-3 mb-md-0">
                                        <div class="order-stats order-stats_delivered">
                                            <div class="order-stats__content"
                                                 style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                <i class="tio-checkmark-circle"></i>
                                                <h6 class="order-stats__subtitle">{{\App\CPU\translate('delivered')}}</h6>
                                            </div>
                                            <div class="order-stats__title">
                                                {{ $delivered_order->count() }}
                                            </div>
                                        </div>
                                    </div>
                                    @php($total_order = App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$seller->id])->where('order_type','default_type')->get())
                                    <div class="col-md-4">
                                        <div class="order-stats order-stats_all">
                                            <div class="order-stats__content"
                                                 style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                <i class="tio-table"></i>
                                                <h6 class="order-stats__subtitle">{{\App\CPU\translate('All')}}</h6>
                                            </div>
                                            <div class="order-stats__title">
                                                {{ $total_order->count() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive datatable-custom">
                                <table id="datatable"
                                       style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                       class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                    <thead class="thead-light thead-50 text-capitalize">
                                    <tr>
                                        <th class="">
                                            {{\App\CPU\translate('SL')}}
                                        </th>
                                        <th>{{\App\CPU\translate('Order')}}</th>
                                        <th>{{\App\CPU\translate('Date')}}</th>
                                        <th>{{\App\CPU\translate('Customer')}}</th>
                                        <th>{{\App\CPU\translate('Payment')}} {{\App\CPU\translate('status')}}</th>
                                        <th>{{\App\CPU\translate('total')}}</th>
                                        <th>{{\App\CPU\translate('Order')}} {{\App\CPU\translate('status')}}</th>
                                        <th class="text-center">{{\App\CPU\translate('action')}}</th>
                                    </tr>
                                    </thead>

                                    <tbody id="set-rows">

                                    @foreach($orders as $key=>$order)

                                        <tr class="status class-all">
                                            <td>
                                                {{$orders->firstItem()+$key}}
                                            </td>
                                            <td>
                                                <a href="{{route('admin.sellers.order-details',['order_id'=>$order['id'],'seller_id'=>$order['seller_id']])}}"
                                                   class="title-color hover-c1">{{$order['id']}}</a>
                                            </td>
                                            <td>{{date('d M Y',strtotime($order['created_at']))}}</td>
                                            <td>
                                                @if($order->customer)
                                                    <a class="text-body text-capitalize"
                                                       href="{{route('admin.customer.view',['user_id'=>$order->customer['id']])}}">
                                                        {{isset($order->customer)?$order->customer['f_name']:''}} {{isset($order->customer)?$order->customer['l_name']:''}}
                                                    </a>
                                                @else
                                                    <label
                                                        class="badge badge-soft-danger fz-12">{{\App\CPU\translate('Removed')}}</label>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->payment_status=='paid')
                                                    <span
                                                        class="badge badge-soft-info fz-12">{{\App\CPU\translate('paid')}}</span>
                                                @else
                                                    <span class="badge badge-soft-danger fz-12">{{\App\CPU\translate('unpaid')}}
                                                </span>
                                                @endif
                                            </td>
                                            <td>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order['order_amount']))}}</td>
                                            <td class="text-capitalize">
                                                @if($order['order_status']=='pending')
                                                    <span
                                                        class="badge badge-soft-info fz-12">{{\App\CPU\translate('pending')}}</span>
                                                @elseif($order['order_status']=='confirmed')
                                                    <span
                                                        class="badge badge-soft-info fz-12">{{\App\CPU\translate('confirmed')}}</span>
                                                @elseif($order['order_status']=='processing')
                                                    <span
                                                        class="badge badge-soft-warning fz-12">{{\App\CPU\translate('processing')}}</span>
                                                @elseif($order['order_status']=='out_for_delivery')
                                                    <span
                                                        class="badge badge-soft-warning fz-12">{{\App\CPU\translate('out_for_delivery')}}</span>
                                                @elseif($order['order_status']=='delivered')
                                                    <span
                                                        class="badge badge-soft-success fz-12">{{\App\CPU\translate('delivered')}}</span>
                                                @else
                                                    <span
                                                        class="badge badge-soft-danger fz-12">{{\App\CPU\translate(str_replace('_',' ',$order['order_status']))}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center">
                                                    <a title="{{\App\CPU\translate('View')}}"
                                                       class="btn btn-outline-info btn-sm square-btn"
                                                       href="{{route('admin.sellers.order-details',['order_id'=>$order['id'],'seller_id'=>$order['customer_id']])}}"><i
                                                            class="tio-invisible"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>

                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Table -->

                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
                                    <!-- Pagination -->
                                    {!! $orders->links() !!}
                                </div>
                            </div>

                            @if(count($orders)==0)
                                <div class="text-center p-4">
                                    <img class="mb-3 w-160"
                                         src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg"
                                         alt="Image Description">
                                    <p class="mb-0">{{\App\CPU\translate('No_data_to_show')}}</p>
                                </div>
                            @endif
                            <!-- End Card -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
