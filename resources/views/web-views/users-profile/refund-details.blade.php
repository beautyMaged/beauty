@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Refund Details'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

@endpush

@section('content')
    <div class="container text-center">
        <h2 class="headerTitle my-3">{{\App\CPU\translate('refund_request')}}</h2>
    </div>
<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4 mt-3 rtl __inline-50"
     style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
    <div class="row">
        <!-- Sidebar-->
    @include('web-views.partials._profile-aside')
    <!-- Content  -->
    @php($product = App\Model\Product::find($order_details->product_id))
    @php($order = App\Model\Order::find($order_details->order_id))
        <section class="col-lg-9 col-md-9">
            <div class="card box-shadow-sm">
                <div class="overflow-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 col-sm-6 col-md-2">
                                    <div>
                                        <img  class="__w-70px __h-70p" onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$refund->product!=null?$refund->product->thumbnail:''}}"
                                        alt="VR Collection">
                                    </div>
                                </div>
                                <div class="col-6 col-sm-6 col-md-6">
                                    <span class="font-bold __text-18px">
                                        @if ($refund->product!=null)
                                            <a href="{{route('product',$refund->product->slug)}}">
                                                {{$refund->product->name}}
                                            </a>
                                        @else
                                            {{\App\CPU\translate('product_name_not_found')}}
                                        @endif
                                    </span> <br>
                                    <span >{{\App\CPU\translate('QTY')}} : {{$refund->order_details->qty}}</span><br>
                                    <span>{{\App\CPU\translate('price')}} : {{\App\CPU\Helpers::currency_converter($refund->order_details->price)}}</span><br>
                                    @if ($order_details->variant!=null)
                                        <span>{{\App\CPU\translate('variant')}} : </span>
                                        {{$order_details->variant}}
                                    @endif
                                </div>
                                <div class="col-12 col-sm-10 col-md-4 text-center d-flex flex-column pl-0 mt-4 mt-sm-4 pl-sm-5">
                                    <div class="row justify-content-md-end mb-3">
                                        <div class="col-md-10 col-lg-10">
                                            <div class="row text-sm-right">
                                                <div class="col-sm-7">{{\App\CPU\translate('total_price')}} : </div>
                                                <div class="col-sm-5 ">
                                                    <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->price*$refund->order_details->qty)}}</strong>
                                                </div>

                                                <div class="col-sm-7">{{\App\CPU\translate('total_discount')}} :</div>
                                                <div class="col-sm-5 ">
                                                    <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->discount)}}</strong>
                                                </div>

                                                <div class="col-sm-7">{{\App\CPU\translate('total_tax')}} :</div>
                                                <div class="col-sm-5">
                                                    <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->tax)}}</strong>
                                                </div>
                                            </div>
                                            <!-- End Row -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $total_product_price = 0;
                    foreach ($order->details as $key => $or_d) {
                        $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
                    }
                        $refund_amount = 0;
                        $subtotal = ($order_details->price * $order_details->qty) - $order_details->discount + $order_details->tax;

                        $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

                        $refund_amount = $subtotal - $coupon_discount;
                    ?>

                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="row text-center">
                                <span class="col-sm-2">{{\App\CPU\translate('subtotal')}} : {{\App\CPU\Helpers::currency_converter($subtotal)}}</span>
                                <span class="col-sm-5">{{\App\CPU\translate('coupon_discount')}} : {{\App\CPU\Helpers::currency_converter($coupon_discount)}}</span>
                                <span class="col-sm-5">{{\App\CPU\translate('total_refund_amount')}} : {{\App\CPU\Helpers::currency_converter($refund_amount)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="col-12">
                                <b>{{\App\CPU\translate('refund_id')}}</b> :
                                <span>{{$refund->id}}</span>
                            </div>
                            <div class="col-12">
                                <b>{{\App\CPU\translate('refund_status')}}</b> :
                                @if ($refund->status == 'pending')
                                <span class="text-capitalize __color-coral"> {{$refund->status}}</span>
                                @elseif($refund->status == 'approved')
                                <span class="text-capitalize __color-1573ff"> {{$refund->status}}</span>
                                @elseif($refund->status == 'refunded')
                                <span class="text-capitalize __color-01ff2cfa"> {{$refund->status}}</span>
                                @elseif($refund->status == 'rejected')
                                <span class="text-capitalize __color-ff2a05fa"> {{$refund->status}}</span>
                                @endif
                            </div>
                            @if ($refund->status == 'rejected')
                            <div class="col-12">
                               <span><b>{{\App\CPU\translate('rejected_reason')}}</b> : </span> {{$refund->rejected_note}}
                            </div>
                            @endif
                            @if ($refund->status == 'refunded')
                            <div class="col-12">
                               <span><b>{{\App\CPU\translate('payment_info')}}</b> : </span> {{$refund->payment_info}}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card box-shadow-sm ">
                        <div class="overflow-auto">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-6">
                                            <h5>{{\App\CPU\translate('refund_reason')}}</h5>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body">
                                    <p>
                                        {{$refund->refund_reason}}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div>
                <div class="card">
                    <div class="card-header">
                        <h5>{{\App\CPU\translate('attachment')}}</h5>
                    </div>
                    <div class="row">
                        <div class="card-body">
                            @if ($refund->images !=null)
                                <div class="gallery">
                                    @foreach (json_decode($refund->images) as $key => $photo)
                                        <a href="{{asset('storage/app/public/refund')}}/{{$photo}}" data-lightbox="mygallery">
                                            <img src="{{asset('storage/app/public/refund')}}/{{$photo}}" alt="">
                                        </a>
                                    @endforeach
                            </div>
                            @else
                                <p>{{\App\CPU\translate('no_attachment_found')}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>
        </section>
    </div>
</div>
@endsection

@push('script')

@endpush
