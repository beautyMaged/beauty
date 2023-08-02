@extends('layouts.back-end.app-seller')

@section('title',\App\CPU\translate('Earning_Statement'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{\App\CPU\translate('Earning_Statement')}}
            </h2>
        </div>
        <!-- End Page Title -->
    @include('seller-views.delivery-man.pages-inline-menu')


    <!-- Page Heading -->
        <div class="flex-between d-sm-flex row align-items-center justify-content-between mb-2 mx-1">
            <div>
                <a href="{{route('seller.delivery-man.list')}}"
                   class="btn btn--primary mt-3 mb-3">{{\App\CPU\translate('Back_to_delivery-man_list')}}</a>
            </div>
            <div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">

                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            <img width="20" class="mb-1" src="{{asset('/public/assets/back-end/img/admin-wallet.png')}}" alt="">
                            {{\App\CPU\translate('Deliveryman_Wallet')}}
                        </h4>
                    </div>
                </div>

                <div class="row g-2" id="order_stats">
                    <div class="col-lg-4">
                        <!-- Card -->
                        <div class="card h-100 d-flex justify-content-center align-items-center">
                            <div class="card-body d-flex flex-column gap-10 align-items-center justify-content-center">
                                <img width="48" src="http://localhost/6valley/public/assets/back-end/img/cc.png" alt="">
                                <h3 class="for-card-count mb-0 fz-24">{{ $delivery_man->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($delivery_man->wallet->cash_in_hand)) : \App\CPU\BackEndHelper::set_symbol(0) }}</h3>
                                <div class="font-weight-bold text-capitalize mb-30">
                                    {{\App\CPU\translate('cash_in_hand')}}
                                </div>
                            </div>
                            <a href="{{ route('seller.delivery-man.collect-cash', ['id' => $delivery_man->id]) }}" class="btn btn--primary mb-4">{{\App\CPU\translate('Collect_Cash')}}</a>
                        </div>
                        <!-- End Card -->
                    </div>
                    <div class="col-lg-8">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="card card-body h-100 justify-content-center py-5">
                                    <div class="d-flex gap-2 justify-content-between align-items-center">
                                        <div class="d-flex flex-column align-items-start">
                                            <h3 class="mb-1 fz-24">{{ $delivery_man->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($delivery_man->wallet->current_balance)) : \App\CPU\BackEndHelper::set_symbol(0)}}</h3>
                                            <div class="text-capitalize mb-0">{{\App\CPU\translate('current_balance')}}</div>
                                        </div>
                                        <div>
                                            <img width="40" src="http://localhost/6valley/public/assets/back-end/img/withdraw-icon.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-body h-100 justify-content-center py-5">
                                    <div class="d-flex gap-2 justify-content-between align-items-center">
                                        <div class="d-flex flex-column align-items-start">
                                            <h3 class="mb-1 fz-24">{{ $delivery_man->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($delivery_man->wallet->total_withdraw)) : \App\CPU\BackEndHelper::set_symbol(0)}}</h3>
                                            <div class="text-capitalize mb-0">{{\App\CPU\translate('total_withdrawn')}}</div>
                                        </div>
                                        <div>
                                            <img width="40" src="http://localhost/6valley/public/assets/back-end/img/aw.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-body h-100 justify-content-center py-5">
                                    <div class="d-flex gap-2 justify-content-between align-items-center">
                                        <div class="d-flex flex-column align-items-start">
                                            <h3 class="mb-1 fz-24">{{$delivery_man->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($delivery_man->wallet->pending_withdraw)) : \App\CPU\BackEndHelper::set_symbol(0)}}</h3>
                                            <div class="text-capitalize mb-0">{{\App\CPU\translate('pending_withdraw')}}</div>
                                        </div>
                                        <div>
                                            <img width="40" class="mb-2" src="{{asset('/public/assets/back-end/img/pw.png')}}" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-body h-100 justify-content-center py-5">
                                    <div class="d-flex gap-2 justify-content-between align-items-center">
                                        <div class="d-flex flex-column align-items-start">
                                            <h3 class="mb-1 fz-24">
                                                {{ $withdrawbale_balance <= 0 ? \App\CPU\BackEndHelper::set_symbol(0) : \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($withdrawbale_balance)) }}
                                            </h3>
                                            <div class="text-capitalize mb-0">{{\App\CPU\translate('withdrawable_balance')}}</div>
                                        </div>
                                        <div>
                                            <img width="40" class="mb-2" src="http://localhost/6valley/public/assets/back-end/img/withdraw.png" alt="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="card-header text-capitalize">
                        <h5 class="mb-0">{{\App\CPU\translate('Delivery_Man')}} {{\App\CPU\translate('Account')}}</h5>
                    </div>
                    <div class="card-body"
                         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="flex-start">
                            <div><h4>{{\App\CPU\translate('Status')}} : </h4></div>
                            <div class="mx-1">
                                <h4>{!! $delivery_man->is_active == 1?'<label class="badge badge-success">Active</label>':'<label class="badge badge-danger">In-Active</label>' !!}</h4>
                            </div>
                        </div>
                        <div class="flex-start">
                            <div><h5 class="text-nowrap">{{\App\CPU\translate('name')}} : </h5></div>
                            <div class="mx-1"><h5>{{$delivery_man->f_name}} {{$delivery_man->l_name}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{\App\CPU\translate('Email')}} : </h5></div>
                            <div class="mx-1"><h5>{{$delivery_man->email}}</h5></div>
                        </div>
                        <div class="flex-start">
                            <div><h5>{{\App\CPU\translate('Phone')}} : </h5></div>
                            <div class="mx-1"><h5>{{$delivery_man->phone}}</h5></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"> {{\App\CPU\translate('bank_info')}}</h5>
                    </div>
                    <div class="card-body"
                         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                        <div class="mt-2">
                            <div class="flex-start">
                                <div><h4>{{\App\CPU\translate('bank_name')}} : </h4></div>
                                <div class="mx-1">
                                    <h4>{{$delivery_man->bank_name ? $delivery_man->bank_name : \App\CPU\translate('No Data found')}}</h4>
                                </div>
                            </div>
                            <div class="flex-start">
                                <div><h6>{{\App\CPU\translate('Branch')}} : </h6></div>
                                <div class="mx-1">
                                    <h6>{{$delivery_man->branch ? $delivery_man->branch : \App\CPU\translate('No Data found')}}</h6>
                                </div>
                            </div>
                            <div class="flex-start">
                                <div><h6>{{\App\CPU\translate('holder_name')}} : </h6></div>
                                <div class="mx-1">
                                    <h6>{{$delivery_man->holder_name ? $delivery_man->holder_name : \App\CPU\translate('No Data found')}}</h6>
                                </div>
                            </div>
                            <div class="flex-start">
                                <div><h6>{{\App\CPU\translate('account_no')}} : </h6></div>
                                <div class="mx-1">
                                    <h6>{{$delivery_man->account_no ? $delivery_man->account_no : \App\CPU\translate('No Data found')}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade py-5" id="exampleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{\App\CPU\translate('Cash_Withdraw')}}</h5>
                    <button id="invoice_close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 mb-3">
                        <div class="d-flex flex-wrap gap-2 mt-3 title-color" id="chosen_price_div">
                            <div class="product-description-label">{{\App\CPU\translate('Total_Cash_In_Hand')}}: </div>
                            <div class="product-price">
                                <strong>{{ $delivery_man->wallet ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($delivery_man->wallet->cash_in_hand)) : 0  }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <input type="number" class="form-control" name="amount" placeholder="Enter Amount to withdraw">
                    </div>
                    <div class="col-md-12 mb-3">
                        <center>
                            <form action="">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <button class="btn btn--primary" data-toggle="modal" data-target="#exampleModal">{{\App\CPU\translate('Collect_Cash')}}</button>
                            </form>
                        </center>
                        <hr class="non-printable">

                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection


@push('script_2')

@endpush

