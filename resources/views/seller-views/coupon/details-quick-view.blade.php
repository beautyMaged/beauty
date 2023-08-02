<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <i class="tio-clear"></i>
</button>
<div class="coupon__details">
    <div class="coupon__details-left">
        <div class="text-center">
            <h6 class="title" id="title">{{ $coupon->title }}</h6>
            <h6 class="subtitle">{{\App\CPU\translate('code')}} : <span id="coupon_code">{{ $coupon->code }}</span></h6>
            <div class="text-capitalize">
                <span>{{\App\CPU\translate(str_replace('_',' ',$coupon->coupon_type))}}</span>
            </div>
        </div>
        <div class="coupon-info">
            <div class="coupon-info-item">
                <span>{{\App\CPU\translate('minimum_purchase')}} :</span>
                <strong id="min_purchase">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon->min_purchase))}}</strong>
            </div>
            @if($coupon->coupon_type != 'free_delivery' && $coupon->discount_type == 'percentage')
            <div class="coupon-info-item" id="max_discount_modal_div">
                <span>{{\App\CPU\translate('maximum_discount')}} : </span>
                <strong id="max_discount">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon->max_discount))}}</strong>
            </div>
            @endif
            <div class="coupon-info-item">
                <span>{{\App\CPU\translate('start_date')}} : </span>
                <span id="start_date">{{ \Carbon\Carbon::parse($coupon->start_date)->format('dS M Y') }}</span>
            </div>
            <div class="coupon-info-item">
                <span>{{\App\CPU\translate('expire_date')}} : </span>
                <span id="expire_date">{{ \Carbon\Carbon::parse($coupon->expire_date)->format('dS M Y') }}</span>
            </div>
            <div class="coupon-info-item">
                <span>{{\App\CPU\translate('discount_bearer')}} : </span>
                <span id="expire_date">{{\App\CPU\translate($coupon->coupon_bearer == 'inhouse' ? 'admin' : $coupon->coupon_bearer)}}</span>
            </div>
        </div>
    </div>
    <div class="coupon__details-right">
        <div class="coupon">
            @if($coupon->coupon_type == 'free_delivery')
                <img src="{{ asset('public/assets/back-end/img/free-delivery.png') }}" alt="Free delivery" width="100">
            @else
                <div class="d-flex">
                    <h4 id="discount">
                        {{$coupon->discount_type=='amount'?\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon->discount)):$coupon->discount.'%'}}
                    </h4>
                </div>

                <span>{{\App\CPU\translate('off')}}</span>
            @endif
        </div>
    </div>
</div>
