@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Refund request'))

@push('css_or_js')
    <link href="{{asset('public/assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9 sidebar_heading">
            <h1 class="h3  mb-0 float-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} headerTitle">
                {{\App\CPU\translate('refund_request')}}
            </h1>
        </div>
    </div>
</div>

<!-- Page Content-->
<div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
     style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
    <div class="row">
        <!-- Sidebar-->
    @include('web-views.partials._profile-aside')
    <!-- Content  -->
    @php($product = App\Model\Product::find($order_details->product_id))
    @php($order = App\Model\Order::find($order_details->order_id))
        <section class="col-lg-9 mt-2 col-md-9">
            <div class="card box-shadow-sm">
                <div class="overflow-auto">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 col-sm-2">
                                    <img class="d-block"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                                        alt="VR Collection" width="60">
                                </div>
                                <div class="col-9 col-sm-7 text-left">
                                    <p>{{$product['name']}}</p>
                                    <span>{{\App\CPU\translate('variant')}} : </span>
                                                {{$order_details->variant}}
                                </div>
                                <div class="col-4 col-sm-3 text-left d-flex flex-column pl-0 mt-2 mt-sm-0 pl-sm-5">
                                    <span >{{\App\CPU\translate('QTY')}} : {{$order_details->qty}}</span>
                                    <span>{{\App\CPU\translate('price')}} : {{\App\CPU\Helpers::currency_converter($order_details->price)}}</span>
                                    <span>{{\App\CPU\translate('discount')}} : {{\App\CPU\Helpers::currency_converter($order_details->discount)}}</span>
                                    <span>{{\App\CPU\translate('tax')}} : {{\App\CPU\Helpers::currency_converter($order_details->tax)}}</span>
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
                                <span class="col-sm-2">{{\App\CPU\translate('subtotal')}}: {{\App\CPU\Helpers::currency_converter($subtotal)}}</span>
                                <span class="col-sm-5">{{\App\CPU\translate('coupon_discount')}}: {{\App\CPU\Helpers::currency_converter($coupon_discount)}}</span>
                                <span class="col-sm-5">{{\App\CPU\translate('total_refundable_amount')}}:{{\App\CPU\Helpers::currency_converter($refund_amount)}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="row">
                                <form action="{{route('refund-store')}}"  method="post" enctype="multipart/form-data">
                                    @csrf 
                                    <input type="hidden" name="order_details_id" value="{{$order_details->id}}">
                                    <input type="hidden" name="amount" value="{{$refund_amount}}">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="input-label" for="name">{{\App\CPU\translate('refund_reason')}}</label>
                                            <textarea class="form-control" name="refund_reason" cols="120" 
                                                   required>{{old('details')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">{{\App\CPU\translate('attachment')}}</label>
                                            <div class="row coba"></div>
                                        </div>
    
                                    </div>
                                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('submit')}}</button>
                                    
                                </form>
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
<script src="{{asset('public/assets/front-end/js/spartan-multi-image-picker.js')}}"></script>
    <script type="text/javascript">
        $(function () {
            $(".coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 5,
                rowHeight: '150px',
                groupClassName: 'col-md-4',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{asset('public/assets/front-end/img/image-place-holder.png')}}',
                    width: '100%'
                },
                dropFileLabel: "{{\App\CPU\translate('drop_here')}}",
                onAddRow: function (index, file) {

                },
                onRenderedPreview: function (index) {

                },
                onRemoveRow: function (index) {

                },
                onExtensionErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('input_png_or_jpg')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                },
                onSizeErr: function (index, file) {
                    toastr.error('{{\App\CPU\translate('file_size_too_big')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });
    </script>
@endpush