@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('refund_details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')

<div class="content container-fluid">
    @php($order = App\Model\Order::find($refund->order_id))
    @php($wallet_status = App\CPU\Helpers::get_business_settings('wallet_status'))
    @php($wallet_add_refund = App\CPU\Helpers::get_business_settings('wallet_add_refund'))

    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/assets/back-end/img/refund_transaction.png')}}" alt="">
            {{\App\CPU\translate('refund_details')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row gy-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row gy-1 justify-content-between align-items-center flex-grow-1">
                        <div class="col-md-4">
                            <h5 class="mb-0">{{\App\CPU\translate('refund_id')}} : {{$refund->id}}</h5>
                        </div>
                        <h5 class="col-md-4 text-capitalize mb-0">
                            {{\App\CPU\translate('refund_status')}}:
                            @if ($refund->status == 'pending')
                                <span  class="text--primary"> {{\App\CPU\translate($refund->status)}}</span>
                            @elseif($refund->status == 'approved')
                                <span class="text-success"> {{\App\CPU\translate($refund->status)}}</span>
                            @elseif($refund->status == 'refunded')
                                <span class="text-info"> {{\App\CPU\translate($refund->status)}}</span>
                            @elseif($refund->status == 'rejected')
                                <span class="text-danger"> {{\App\CPU\translate($refund->status)}}</span>
                            @endif
                        </h5>
                        <div class="col-md-4 d-flex justify-content-md-end">
                            <button class="btn btn--primary" data-toggle="modal"
                                    data-target="#refund-status">{{\App\CPU\translate('change_refund_status')}}</button>
                        </div>
                    </div>
                </div>

                <?php
                    $total_product_price = 0;
                    foreach ($order->details as $key => $or_d) {
                        $total_product_price += ($or_d->qty*$or_d->price) + $or_d->tax - $or_d->discount;
                    }
                        $refund_amount = 0;
                        $subtotal = $refund->order_details->price*$refund->order_details->qty - $refund->order_details->discount + $refund->order_details->tax;

                        $coupon_discount = ($order->discount_amount*$subtotal)/$total_product_price;

                        $refund_amount = $subtotal - $coupon_discount;
                ?>
                <div class="card-body">
                    <div class="row gy-2">
                        <div class="col-sm-4 col-md-4 col-lg-2">
                            <div >
                                <img onerror="this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
                                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$refund->product!=null?$refund->product->thumbnail:''}}"
                                alt="VR Collection" >
                            </div>
                        </div>
                        <div class="col-sm-8 col-md-4 col-lg-6">
                            <h4>
                                @if ($refund->product!=null)
                                    <a href="{{route('admin.product.view',[$refund->product->id])}}">
                                        {{$refund->product->name}}
                                    </a>
                                @else
                                    {{\App\CPU\translate('product_name_not_found')}}
                                @endif
                            </h4>
                            <div class="mb-1">{{\App\CPU\translate('QTY')}} : {{$refund->order_details->qty}}</div>
                            <div class="mb-1">{{\App\CPU\translate('price')}} : {{\App\CPU\Helpers::currency_converter($refund->order_details->price)}}</div>

                            @if ($refund->order_details->variant)
                                <div class="d-flex flex-wrap gap-1">
                                    <strong>{{\App\CPU\translate('Variation')}} : </strong>

                                    <div>{{$refund->order_details->variant}}</div>
                                </div>
                            @endif

                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="row justify-content-md-end mb-3">
                                <div class="col-md-10 col-lg-10">
                                    <dl class="row text-md-right">
                                        <dt class="col-md-7">{{\App\CPU\translate('total_price')}} : </dt>
                                        <dd class="col-md-5 ">
                                            <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->price*$refund->order_details->qty)}}</strong>
                                        </dd>

                                        <dt class="col-md-7">{{\App\CPU\translate('total_discount')}} :</dt>
                                        <dd class="col-md-5 ">
                                            <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->discount)}}</strong>
                                        </dd>

                                        <dt class="col-md-7">{{\App\CPU\translate('total_tax')}} :</dt>
                                        <dd class="col-md-5">
                                            <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->tax)}}</strong>
                                        </dd>
                                    </dl>
                                    <!-- End Row -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex flex-wrap flex-column flex-md-row gap-10 justify-content-between">
                        <span class="title-color">{{\App\CPU\translate('subtotal')}} : {{\App\CPU\Helpers::currency_converter($subtotal)}}</span>
                        <span class="title-color">{{\App\CPU\translate('coupon_discount')}} : {{\App\CPU\Helpers::currency_converter($coupon_discount)}}</span>
                        <span class="title-color">{{\App\CPU\translate('total_refund_amount')}} : {{\App\CPU\Helpers::currency_converter($refund_amount)}}</span>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{\App\CPU\translate('additional_information')}}</h4>
                </div>

                <div class="card-body">
                    <div class="row gy-2">
                        <div class="col-sm-6 col-md-4 d-flex flex-column gap-10">
                            <h5>{{\App\CPU\translate('seller_info')}} : </h5>

                            @if ($order->seller_is=='seller' && $order->seller!=null)
                                <div>{{\App\CPU\translate('seller_name')}} :
                                    <a class="text-dark"
                                    href="{{ route('admin.sellers.view', [$order->seller->id]) }}">
                                        {{$order->seller->f_name . ' '. $order->seller->l_name}}
                                    </a>
                                </div>
                                <div>{{\App\CPU\translate('seller_email')}} : <a
                                    class="text-dark"
                                    href="mailto:{{ $order->seller->email }}">{{$order->seller->email}}</a>
                                </div>
                                <div>{{\App\CPU\translate('seller_phone')}} :
                                    <a class="text-dark"
                                        href="tel:{{ $order->seller->phone }}">{{$order->seller->phone}}
                                    </a>
                                </div>
                            @elseif($order->seller_is=='admin')
                            <div>{{\App\CPU\translate('inhouse_product')}} </div>
                            @else
                                <div>{{\App\CPU\translate('seller_not_found')}} </div>
                            @endif
                        </div>

                        <div class="col-sm-6 col-md-4 d-flex flex-column gap-10">
                            <h5>{{\App\CPU\translate('deliveryman_info')}} : </h5>
                            <div>{{\App\CPU\translate('deliveryman_name')}} : {{$order->delivery_man!=null?$order->delivery_man->f_name . ' ' .$order->delivery_man->l_name:\App\CPU\translate('not_assigned')}}</div>
                            <div>{{\App\CPU\translate('deliveryman_email')}} : {{$order->delivery_man!=null?$order->delivery_man->email :\App\CPU\translate('not_found')}}</div>
                            <div>{{\App\CPU\translate('deliveryman_phone')}} : {{$order->delivery_man!=null?$order->delivery_man->phone :\App\CPU\translate('not_found')}}</div>
                        </div>

                        <div class="col-sm-6 col-md-4 d-flex flex-column gap-10">
                            <div>{{\App\CPU\translate('payment_method')}} : {{str_replace('_',' ',$order->payment_method)}}</div>
                            <div class="d-flex flex-wrap gap-2">{{\App\CPU\translate('order_details')}} : <a class="btn btn--primary btn-sm" href="{{route('admin.orders.details',['id'=>$order->id])}}">{{\App\CPU\translate('click_here')}}</a></div>
                            <div class="d-flex flex-wrap gap-2">
                                {{\App\CPU\translate('customer_details')}} :
                                @if ($refund->customer)
                                    <a class="btn btn--primary btn-sm"
                                        href="{{route('admin.customer.view',[$refund->customer->id])}}">
                                        {{\App\CPU\translate('click_here')}}
                                    </a>
                                @else
                                    <a class="btn btn-warning btn-sm"
                                        href="#">
                                        {{\App\CPU\translate('not_found')}}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{\App\CPU\translate('refund_status_changed_log')}}</h4>
                </div>

                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>
                                {{\App\CPU\translate('SL')}}
                            </th>
                            <th>{{\App\CPU\translate('changed_by')}} </th>
                            <th>{{\App\CPU\translate('status')}}</th>
                            <th>{{\App\CPU\translate('note')}}</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach ($refund->refund_status as $key=>$r_status)
                            <tr>
                                <td>
                                    {{$key+1}}
                                </td>
                                <td>
                                    {{$r_status->change_by}}
                                </td>
                                <td>
                                    {{\App\CPU\translate($r_status->status)}}
                                </td>
                                <td class="text-break">
                                    <div class="word-break max-w-360px">
                                        {{$r_status->message}}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if(count($refund->refund_status)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{\App\CPU\translate('refund_reason')}}</h4>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <p>
                            {{$refund->refund_reason}}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{\App\CPU\translate('attachment')}}</h4>
                </div>
                <div class="row">
                    <div  class="card-body">
                        @if ($refund->images !=null)
                            <div class="gallery grid-gallery">
                                @foreach (json_decode($refund->images) as $key => $photo)
                                    <a href="{{asset('storage/refund')}}/{{$photo}}" data-lightbox="mygallery">
                                        <img src="{{asset('storage/refund')}}/{{$photo}}" alt="">
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

<div class="modal fade" id="refund-status" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{\App\CPU\translate('change_refund_status')}}</h5>
                <button id="payment_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.refund-section.refund.refund-status-update')}}" id='order_place' method="post" class="row">
                    @csrf
                    <input type="hidden" name="id" value="{{$refund->id}}">
                    <div class="form-group col-12">
                        <label class="input-label" for="">{{\App\CPU\translate('refund_status')}}</label>
                        <select name="refund_status" class="form-control" onchange="refund_status_change(this.value)">
                            <option
                            value="pending" {{$refund->status=='pending'?'selected':''}}>
                            {{ \App\CPU\translate('pending')}}
                        </option>
                        <option
                            value="approved" {{$refund->status=='approved'?'selected':''}}>
                            {{ \App\CPU\translate("approved")}}
                        </option>
                        <option
                            value="refunded" {{$refund->status=='refunded'?'selected':''}}>
                            {{ \App\CPU\translate("refunded")}}
                        </option>
                        <option
                            value="rejected" {{$refund->status=='rejected'?'selected':''}}>
                            {{ \App\CPU\translate("rejected")}}
                        </option>
                        </select>
                    </div>

                    <div class="form-group col-12" id="approved">
                        <label class="input-label" for="">{{\App\CPU\translate('approved_note')}}</label>
                        <input type="text" class="form-control" id="approved_note" name="approved_note">
                    </div>
                    <div class="form-group col-12" id="rejected">
                        <label class="input-label" for="">{{\App\CPU\translate('rejected_note')}}</label>
                        <input type="text" class="form-control" id="rejected_note" name="rejected_note">
                    </div>

                    <div class="form-group col-12" id="payment_option">
                        <label class="input-label" for="">{{\App\CPU\translate('payment_method')}}</label>
                        <select class="form-control" name="payment_method" id="payment_method">
                            <option value="cash">{{\App\CPU\translate('cash')}}</option>
                            <option value="digitally_paid">{{\App\CPU\translate('digitally_paid')}}</option>
                            @if ($wallet_status == 1 && $wallet_add_refund == 1)
                                <option value="customer_wallet">{{\App\CPU\translate('customer_wallet')}}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group col-12" id="refunded">
                        <label class="input-label" for="">{{\App\CPU\translate('payment_info')}}</label>
                        <input type="text" class="form-control" id="payment_info" name="payment_info">
                    </div>

                    <div class="form-group col-12">
                        <button class="btn btn--primary float-right" type="submit">{{\App\CPU\translate('submit')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script_2')

<script>
    $( document ).ready(function() {

        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);
        $('#refunded').hide();
        $("#payment_info").prop("required", false);
});
function refund_status_change(val)
{
    if(val === 'approved'){
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);
        $('#refunded').hide();
        $("#payment_info").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);

        $('#approved').show();
        $("#approved_note").prop("required", true);

    }else if(val === 'rejected'){
        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#refunded').hide();
        $("#payment_info").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);

        $('#rejected').show();
        $("#rejected_note").prop("required", true);

    }else if(val === 'refunded')
    {
        Swal.fire({
            title: 'Are you sure! After refunded you can not change it!!',
            type: 'warning',
        });

        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);

        $('#refunded').show();
        $("#payment_info").prop("required", true);
        $('#payment_option').show();
        $("#payment_method").prop("required", true);
    }else{
        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);

        $('#refunded').hide();
        $("#payment_info").prop("required", false);
        $('#payment_option').hide();
        $("#payment_method").prop("required", false);
    }
}
</script>
    <script>
        function refund_stats_update(val) {
            let id = '{{$refund->id}}'
            Swal.fire({
            title: '{{\App\CPU\translate('Are_you_sure?')}}',
            text: '{{\App\CPU\translate('You_want_to_change_refund_status!!')}}',
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '#161853',
            cancelButtonText: 'No',
            confirmButtonText: 'Yes',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.refund-section.refund.refund-status-update')}}',
                data: {
                    id:id,
                    status:val,
                },
                success: function (data) {
                    //console.log(data);
                    if(data === 'success'){
                        toastr.success('{{\App\CPU\translate('refund_status_updated_successfully!!')}}!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else{
                        toastr.warning('{{\App\CPU\translate('it is already refunded, you can not change it!!')}}!', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                },
            });
        }
        })
        };
    </script>
@endpush
