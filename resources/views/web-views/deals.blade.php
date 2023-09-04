@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Flash Deal Products'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Deals of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Deals of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <style>
        .countdown-background{
            background: {{$web_config['primary_color']}};
        }
        .cz-countdown-days {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-hours {
            border: .5px solid{{$web_config['primary_color']}};
        }

        .cz-countdown-minutes {
            border: .5px solid{{$web_config['primary_color']}};
        }
        .cz-countdown-seconds {
            border: .5px solid{{$web_config['primary_color']}};
        }
        .flash_deal_product_details .flash-product-price {
            color: {{$web_config['primary_color']}};
        }
    </style>
@endpush

@section('content')
@php($decimal_point_settings = \App\CPU\Helpers::get_business_settings('decimal_point_settings'))
<div class="row mb-3 __inline-35"
     style="background:{{$web_config['primary_color']}}10;">
    <div class="container" dir="rtl">
        <div class="row">
            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-9 pl-3 py-4 serial_route">
                <a href="{{route('home')}}" class="bold">الصفحة الرئيسية</a>
                <div class="d-inline-block position-relative" style="width: 25px"><i
                        style="position: absolute;top: -15px;right: 3px;"
                        class="fa-solid fa-chevron-left mt-1  px-1 "></i></div>
                <span class="bold">العروض اليومية</span>

            </div>

            <div class="col-xxl-6 col-xl-6 col-lg-6 col-md-6 col-sm-6 col-3 py-4 text-left back">
                <a href="{{ url()->previous() }}" class="bold">العودة</a>
                <div class="d-inline-block position-relative" style="width: 25px"><i
                        style="position: absolute;top: -15px;right: 3px;"
                        class="fa-solid fa-chevron-left mt-1  px-1 "></i></div>

            </div>
        </div>

    </div>
</div>
<div class="__inline-59">

    <div class="for-banner container">

        <img class="d-block for-image"
             onerror="this.onerror=null;this.src='{{asset('assets/front-end/img/image-place-holder.png')}}'"
             src="{{asset('storage/deal')}}/{{$deal['banner']}}"
             alt="Shop Converse">

    </div>
    <div class="container md-4 mt-3 rtl" dir="rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row g-3 flex-center align-items-center" dir="rtl">
            @php($flash_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'flash_deal'])->whereDate('start_date','<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->first())
            <div class="col-sm-auto text-center {{Session::get('direction') === "rtl" ? 'text-sm-left' : 'text-sm-right'}}">
                <span class="flash_deal_title ">
{{--                    {{ \App\CPU\translate('flash_deal')}}--}}
                    العروض اليومية
                </span>
            </div>
            <div class="col-sm-auto ">
                <div class="countdown-background __countdown mx-auto">
                    <span class="cz-countdown d-flex justify-content-center align-items-center num_fam"
                        data-countdown="{{isset($flash_deals)?date('m/d/Y',strtotime($flash_deals['end_date'])):''}} 11:59:00 PM">
                        <span class="cz-countdown-days align-items-center">
                            <span class="cz-countdown-value"></span>
{{--                            <span>{{ \App\CPU\translate('day')}}</span>--}}
                            <span>يوم</span>
                        </span>
                        <span class="cz-countdown-value p-1">:</span>
                        <span class="cz-countdown-hours align-items-center">
                            <span class="cz-countdown-value"></span>
{{--                            <span>{{ \App\CPU\translate('hrs')}}</span>--}}
                            <span>ساعة</span>
                        </span>
                        <span class="cz-countdown-value p-1">:</span>
                        <span class="cz-countdown-minutes align-items-center">
                            <span class="cz-countdown-value"></span>
{{--                            <span>{{ \App\CPU\translate('min')}}</span>--}}
                            <span>دقيقة</span>
                        </span>
                        <span class="cz-countdown-value p-1">:</span>
                        <span class="cz-countdown-seconds align-items-center">
                            <span class="cz-countdown-value"></span>
{{--                            <span>{{ \App\CPU\translate('sec')}}</span>--}}
                            <span>ثانية</span>
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    <!-- Toolbar-->

    <!-- Products grid-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <section class="col-lg-12">
                <div class="row mt-4" dir="rtl">
                    @if($discountPrice)
                        @foreach($deal->products as $dp)
                            @if (isset($dp->product))
                                <div class="col-xl-2 col-sm-3 col-6 __mb-10px">

                                    @include('web-views.partials._single-product',['product'=>$dp->product,'decimal_point_settings'=>$decimal_point_settings])


                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('script')

@endpush
