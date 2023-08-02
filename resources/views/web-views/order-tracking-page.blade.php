@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Track Order Result'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/app/public/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
    <style>
       .closet{
            float: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};
        }
    </style>
@endpush

@section('content')
    <!-- Page Content-->
    <div class="container rtl py-5" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="__max-w-620 mx-auto">
            <h3 class="text-center text-capitalize">{{\App\CPU\translate('track_order')}}</h3>
            <div class="card __card">
                <div class="card-body">
                    <form action="{{route('track-order.result')}}" type="submit" method="post" class="p-3">
                        @csrf

                        @if(session()->has('Error'))
                            <div class="alert alert-danger alert-block">
                                <span type="" class="closet __closet" data-dismiss="alert">×</span>
                                <strong>{{ session()->get('Error') }}</strong>
                            </div>
                        @endif

                        <div class="form-group mb-4">
                            <input class="form-control prepended-form-control" type="text" name="order_id"
                                placeholder="{{\App\CPU\translate('order_id')}}" required>
                        </div>
                        <div class="form-group mb-4">
                            <input class="form-control prepended-form-control" type="text" name="phone_number"
                                placeholder="{{\App\CPU\translate('your_phone_number')}}" required>
                        </div>
                        <div class="text-right">
                            <button class="btn btn--primary" type="submit" name="trackOrder">{{\App\CPU\translate('track_order')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script src="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.js">
    </script>
@endpush
