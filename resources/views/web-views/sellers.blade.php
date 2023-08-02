@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('All Seller Page'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="Brands of {{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="Brands of {{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <style>
        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}    !important;
        }
    </style>
@endpush

@section('content')

    <!-- Page Content-->
    <div class="container mb-md-4 {{Session::get('direction') === "rtl" ? 'rtl' : ''}} __inline-65">
        <div class="row mt-3 mb-3 border-bottom">
            <div class="col-md-8">
                <h4 class="mt-2 text-start">{{ \App\CPU\translate('All_Sellers') }}</h4>
            </div>
            <div class="col-md-4">
                <form action="{{route('search-shop')}}">
                    @csrf
                    <div class="input-group mb-3">
                        <input type="text" class="form-control"  placeholder="{{\App\CPU\translate('Shop name')}}" name="shop_name" required>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">{{\App\CPU\translate('Search')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <!-- Content  -->
            <section class="col-lg-12">
                <!-- Products grid-->
                <div class="row mx-n2 __min-h-200px">
                    @foreach($sellers as $shop)
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 px-2 pb-4 text-center">
                            <div class="card-body shadow position-relative">
                                @php($current_date = date('Y-m-d'))
                                @php($start_date = date('Y-m-d', strtotime($shop['vacation_start_date'])))
                                @php($end_date = date('Y-m-d', strtotime($shop['vacation_end_date'])))
                                @if($shop->vacation_status && ($current_date >= $start_date) && ($current_date <= $end_date))
                                    <a href="{{route('shopView',['id'=>$shop['seller_id']])}}">
                                        <span class="temporary-closed">
                                            <span>{{\App\CPU\translate('closed_now')}}</span>
                                        </span>
                                    </a>
                                @elseif($shop->temporary_close)
                                    <a href="{{route('shopView',['id'=>$shop['seller_id']])}}">
                                        <span class="temporary-closed">
                                            <span>{{\App\CPU\translate('closed_now')}}</span>
                                        </span>
                                    </a>
                                @endif
                                <a href="{{route('shopView',['id'=>$shop['seller_id']])}}">
                                    <img class="__inline-66"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset("storage/app/public/shop/$shop->image")}}"
                                         alt="{{$shop->name}}">
                                    <div class="text-center text-dark">
                                        <span class="text-center font-weight-bold small p-1">{{Str::limit($shop->name, 14)}}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row mx-n2">
                    <div class="col-md-12">
                        <center>
                            {{ $sellers->links() }}
                        </center>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')

@endpush
