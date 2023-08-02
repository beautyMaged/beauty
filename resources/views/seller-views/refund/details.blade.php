@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('refund_details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')

<div class="content container-fluid">

    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
            <img width="20" src="{{asset('/public/assets/back-end/img/refund-request-list.png')}}" alt="">
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

                        <h5 class="col-md-4 mb-0 text-capitalize">{{\App\CPU\translate('refund_status')}}:
                            @if ($refund->status == 'pending')
                                <span class="text--primary"> {{\App\CPU\translate($refund->status)}}</span>
                            @elseif($refund->status == 'approved')
                                <span class="text-success"> {{\App\CPU\translate($refund->status)}}</span>
                            @elseif($refund->status == 'refunded')
                                <span class="text-info"> {{\App\CPU\translate($refund->status)}}</span>
                            @elseif($refund->status == 'rejected')
                                <span class="text-danger"> {{\App\CPU\translate($refund->status)}}</span>
                            @endif
                        </h5>

                        <div class="col-md-4 d-flex justify-content-md-end">
                            @if ($refund->change_by != 'admin')
                                <button class="btn btn--primary" data-toggle="modal"
                                        data-target="#refund-status">{{\App\CPU\translate('change_refund_status')}}</button>
                            @endif
                        </div>

                    </div>
                </div>
                @php($order = App\Model\Order::find($refund->order_id))
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
                        <div class="col-sm-4 col-lg-2">
                            <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$refund->product!=null?$refund->product->thumbnail:''}}"
                                alt="VR Collection">
                        </div>
                        <div class="col-sm-8 col-md-4 col-lg-6">
                            <h4>
                                @if ($refund->product!=null)
                                    <a href="{{route('seller.product.view',[$refund->product->id])}}">
                                        {{$refund->product->name}}
                                    </a>
                                @else
                                    {{\App\CPU\translate('product_name_not_found')}}
                                @endif
                            </h4>
                            <div >{{\App\CPU\translate('QTY')}} : {{$refund->order_details->qty}}</div>
                            <div>{{\App\CPU\translate('price')}} : {{\App\CPU\Helpers::currency_converter($refund->order_details->price)}}</div>
                            @if ($refund->order_details->variant)
                                <strong><u>{{\App\CPU\translate('Variation')}} : </u></strong>

                                <div class="font-size-sm text-body">
                                    <span class="font-weight-bold">{{$refund->order_details->variant}}</span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4 col-lg-4">
                            <div class="row justify-content-md-end mb-3">
                                <div class="col-md-10">
                                    <dl class="row text-md-right">
                                        <dt class="col-md-7">{{\App\CPU\translate('total_price')}} : </dt>
                                        <dd class="col-md-5 ">
                                            <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->price*$refund->order_details->qty)}}</strong>
                                        </dd>

                                        <dt class="col-sm-7">{{\App\CPU\translate('total_discount')}} :</dt>
                                        <dd class="col-sm-5 ">
                                            <strong>{{\App\CPU\Helpers::currency_converter($refund->order_details->discount)}}</strong>
                                        </dd>

                                        <dt class="col-sm-7">{{\App\CPU\translate('total_tax')}} :</dt>
                                        <dd class="col-sm-5">
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
        <div class="col-12 ">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">{{\App\CPU\translate('additional_information')}}</h4>
                </div>

                <div class="card-body">
                    <div class="row gy-2">
                        <div class="col-sm-6 col-md-4 d-flex flex-column gap-10">
                            <h5>{{\App\CPU\translate('deliveryman_info')}} : </h5>
                            <span>{{\App\CPU\translate('deliveryman_name')}} : {{$order->delivery_man!=null?$order->delivery_man->f_name . ' ' .$order->delivery_man->l_name:\App\CPU\translate('not_assigned')}}</span>
                            <span>{{\App\CPU\translate('deliveryman_email')}} : {{$order->delivery_man!=null?$order->delivery_man->email :\App\CPU\translate('not_found')}}</span>
                            <span>{{\App\CPU\translate('deliveryman_phone')}} : {{$order->delivery_man!=null?$order->delivery_man->phone :\App\CPU\translate('not_found')}}</span>
                        </div>

                        <div class="col-sm-6 col-md-4 d-flex flex-column gap-10">
                            <span>{{\App\CPU\translate('payment_method')}} : {{str_replace('_',' ',$order->payment_method)}}</span>
                            <span>{{\App\CPU\translate('order_details')}} : <a class="btn btn--primary btn-sm" href="{{route('seller.orders.details',['id'=>$order->id])}}">{{\App\CPU\translate('click_here')}}</a></span>
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
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>
                                {{\App\CPU\translate('SL')}}#
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
                            <img class="mb-3 w-160" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
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
                <div class="card-body">
                    <div class="col-12">

                        @if ($refund->images !=null)
                            <div class="gallery grid-gallery">
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
                <form action="{{route('seller.refund.refund-status-update')}}" id='order_place' method="post" class="row">
                    @csrf
                    <input type="hidden" name="id" value="{{$refund->id}}">
                    <div class="form-group col-12">
                        <label class="input-label" for="">{{\App\CPU\translate('refund_status')}}</label>
                        <select name="refund_status" class="form-control" onchange="refund_status_change(this.value)">
                            <option
                                value="pending" {{$refund->status=='pending'?'selected':''}}>
                                {{ \App\CPU\translate('pending')}}
                            </option>
                            {{-- @if ($refund->change_by !='admin') --}}
                                <option
                                    value="approved" {{$refund->status=='approved'?'selected':''}}>
                                    {{ \App\CPU\translate("approved")}}
                                </option>

                                <option
                                    value="rejected" {{$refund->status=='rejected'?'selected':''}}>
                                    {{ \App\CPU\translate("rejected")}}
                                </option>
                            {{-- @endif --}}
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
                    <div class="form-group col-12 d-flex justify-content-end">
                        <button class="btn btn--primary" type="submit">{{\App\CPU\translate('submit')}}</button>
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
});

function refund_status_change(val)
{
    if(val === 'approved'){
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);

        $('#approved').show();
        $("#approved_note").prop("required", true);

    }else if(val === 'rejected'){
        $('#approved').hide();
        $("#approved_note").prop("required", false);

        $('#rejected').show();
        $("#rejected_note").prop("required", true);

    }else{
        $('#approved').hide();
        $("#approved_note").prop("required", false);
        $('#rejected').hide();
        $("#rejected_note").prop("required", false);

    }
}
</script>
@endpush
