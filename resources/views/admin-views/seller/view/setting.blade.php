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
                {{\App\CPU\translate('seller_details')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Page Heading -->
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                <a href="{{route('admin.sellers.seller-list')}}" class="btn btn--primary mt-3 mb-3">{{\App\CPU\translate('Back_to_seller_list')}}</a>
            </div>
            <div>
                @if ($seller->status=="pending")
                    <div class="mt-4 pr-2">
                        <div class="flex-start">
                            <div class="mx-1"><h4><i class="tio-shop-outlined"></i></h4></div>
                            <div><h4>{{\App\CPU\translate('Seller request for open a shop')}}.</h4></div>
                        </div>
                        <div class="text-center">
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn--primary btn-sm">{{\App\CPU\translate('Approve')}}</button>
                            </form>
                            <form class="d-inline-block" action="{{route('admin.sellers.updateStatus')}}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$seller->id}}">
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-danger btn-sm">{{\App\CPU\translate('reject')}}</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Page Header -->
        <div class="page-header">
            <div class="flex-between mx-1 row">
                <div>
                    <h1 class="page-header-title">{{ $seller->shop ? $seller->shop->name : "Shop Name : Update Please" }}</h1>
                </div>

            </div>
            <!-- Nav Scroller -->
            <div class="js-nav-scroller hs-nav-scroller-horizontal">
                <!-- Nav -->
                <ul class="nav nav-tabs flex-wrap page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link " href="{{ route('admin.sellers.view',$seller->id) }}">{{\App\CPU\translate('Shop')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link "
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'order']) }}">{{\App\CPU\translate('Order')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('admin.sellers.view',['id'=>$seller->id, 'tab'=>'product']) }}">{{\App\CPU\translate('Product')}}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"
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

        <div class="row">
            <div class="col-md-6 mt-3">
                <form action="{{ url()->current() }}"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                      method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"> {{\App\CPU\translate('Sales Commission')}} : </h5>
                            <label class="switcher">
                                <input type="checkbox" name="commission_status"
                                       class="switcher_input"
                                       value="1" {{$seller['sales_commission_percentage']!=null?'checked':''}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <small class="badge badge-soft-info text-wrap mb-3">
                                {{\App\CPU\translate('If sales commission is disabled here, the system default commission will be applied')}}.
                            </small>
                            <div class="form-group">
                                <label>{{\App\CPU\translate('Commission')}} ( % )</label>
                                <input type="number" value="{{$seller['sales_commission_percentage']}}"
                                       class="form-control" name="commission">
                            </div>
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Update')}}</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6 mt-3">
                <form action="{{ url()->current() }}"
                      style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                      method="GET">
                    @csrf
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"> {{\App\CPU\translate('GST Number')}} : </h5>
                            <label class="switcher">
                                <input type="checkbox" name="gst_status"
                                       class="switcher_input"
                                       value="1" {{$seller['gst']!=null?'checked':''}}>
                                <span class="switcher_control"></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <small class="badge text-wrap badge-soft-info mb-3">
                                {{\App\CPU\translate('If GST number is disabled here, it will not show in invoice')}}.
                            </small>
                            <div class="form-group">
                                <label> {{\App\CPU\translate('Number')}}  </label>
                                <input type="text" value="{{$seller['gst']}}"
                                       class="form-control" name="gst">
                            </div>
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Update')}} </button>
                        </div>
                    </div>
                </form>
            </div>
{{--            <div class="col-md-6 mt-2">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header">--}}
{{--                        <h5 class="mb-0">{{\App\CPU\translate('Seller POS')}}</h5>--}}
{{--                    </div>--}}

{{--                    <div class="card-body">--}}
{{--                        <form action="{{ url()->current() }}"--}}
{{--                              method="GET">--}}
{{--                            @csrf--}}
{{--                            <label class="title-color d-flex">{{\App\CPU\translate('Seller POS permission on/off')}}</label>--}}
{{--                            <div class="d-flex align-items-center gap-2 mb-1">--}}
{{--                                <input class="" name="seller_pos" type="radio" value="1"--}}
{{--                                       id="seller_pos1" {{$seller['pos_status']==1?'checked':''}}>--}}
{{--                                <label class="mb-0" for="seller_pos1">--}}
{{--                                    {{\App\CPU\translate('Turn on')}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="d-flex align-items-center gap-2">--}}
{{--                                <input class="" name="seller_pos" type="radio" value="0"--}}
{{--                                       id="seller_pos2" {{$seller['pos_status']==0?'checked':''}}>--}}
{{--                                <label class="mb-0" for="seller_pos2">--}}
{{--                                    {{\App\CPU\translate('Turn off')}}--}}
{{--                                </label>--}}
{{--                            </div>--}}
{{--                            <div class="d-flex justify-content-end pt-3">--}}
{{--                                <button type="submit" class="btn btn--primary">{{\App\CPU\translate('Save')}}</button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
@endsection

@push('script')

@endpush
