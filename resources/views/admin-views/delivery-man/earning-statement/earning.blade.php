@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Earning_Statement'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4 pb-2">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{\App\CPU\translate('earning_statement')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.delivery-man.pages-inline-menu')

        <div class="card mb-3">
            <div class="card-body">

                <div class="row justify-content-between align-items-center g-2 mb-3">
                    <div class="col-sm-6">
                        <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                            {{ \App\CPU\translate('earning_statement') }}
                        </h4>
                    </div>
                </div>

                <div class="row g-2">
                    <div class="col-sm-6 col-lg-4">
                        <!-- Total Earning Card -->
                        <div class="business-analytics">
                            <h5 class="business-analytics__subtitle">{{ \App\CPU\translate('total_earning') }}</h5>
                            <h2 class="business-analytics__title">{{ $total_earn ? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total_earn)) : \App\CPU\BackEndHelper::set_symbol(0) }}</h2>
                            <img src="{{ asset('public/assets/back-end/img/aw.png') }}" width="40" class="business-analytics__img" alt="">
                        </div>
                        <!-- End Total Earning Card -->
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <!-- Withdrawable balance Card -->
                        <div class="business-analytics">
                            <h5 class="business-analytics__subtitle">{{ \App\CPU\translate('withdrawable_balance') }}</h5>
                            <h2 class="business-analytics__title">{{ $withdrawable_balance <= 0 ? \App\CPU\BackEndHelper::set_symbol(0) : \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($withdrawable_balance)) }}</h2>
                            <img src="{{ asset('public/assets/back-end/img/pw.png') }}" width="40" class="business-analytics__img" alt="">
                        </div>
                        <!-- End Withdrawable balance Card -->
                    </div>
                    <div class="col-sm-6 col-lg-4">
                        <!-- Business Analytics Card -->
                        <div class="business-analytics">
                            <h5 class="business-analytics__subtitle">{{ \App\CPU\translate('withdrawn') }}</h5>
                            <h2 class="business-analytics__title">{{ $delivery_man->wallet? \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($delivery_man->wallet->total_withdraw)) : \App\CPU\BackEndHelper::set_symbol(0) }}</h2>
                            <img src="{{ asset('public/assets/back-end/img/withdraw.png') }}" width="40" class="business-analytics__img" alt="">
                        </div>
                        <!-- End Business Analytics Card -->
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                            <h4 class="d-flex align-items-center text-capitalize gap-10 mb-0">
                                {{ \App\CPU\translate('earning_statement') }}
                                <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $orders->total() }}</span>
                            </h4>
                        </div>
                        <div class="col-md-8 col-lg-6">
                            <div class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                <!-- Search -->
                                <form action="" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control" placeholder="{{ \App\CPU\translate('Search_by_order_no') }}" aria-label="Search orders" value="{{ $search }}">
                                        <button type="submit" class="btn btn--primary">
                                            {{ \App\CPU\translate('Search') }}
                                        </button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row g-2">
                    <div class="col-sm-12 mb-3">
                        <!-- Card -->
                        <div class="card">

                            <!-- Table -->
                            <div class="table-responsive datatable-custom">
                                <table class="table table-hover table-borderless table-thead-bordered table-align-middle card-table text-left">
                                    <thead class="thead-light thead-50 text-capitalize table-nowrap">
                                    <tr>
                                        <th>{{ \App\CPU\translate('SL') }}</th>
                                        <th>{{ \App\CPU\translate('order_no') }}</th>
                                        <th>{{ \App\CPU\translate('earning') }}</th>
                                        <th class="text-center">{{ \App\CPU\translate('status') }}</th>
                                    </tr>
                                    </thead>

                                    <tbody id="set-rows">
                                    @forelse($orders as $key=>$order)
                                    <tr>
                                        <td>{{ $orders->firstItem()+$key }}</td>
                                        <td>
                                            <div class="media align-items-center gap-10 flex-wrap">
                                                <a class="title-color" title="{{\App\CPU\translate('order_details')}}"
                                                   href="{{route('admin.orders.details',['id'=>$order['id']])}}">
                                                    {{ $order->id }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="media-body">{{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($order->deliveryman_charge)) }}</div>

                                            </div>
                                        </td>
                                        <td class="text-center text-capitalize">
                                            @if($order['order_status']=='pending')
                                                <span class="badge badge-soft-info fz-12">
                                                    {{$order['order_status']}}
                                            </span>

                                            @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                                                <span class="badge badge-soft-warning fz-12">
                                                {{str_replace('_',' ',$order['order_status'] == 'processing' ? 'packaging':$order['order_status'])}}
                                            </span>
                                            @elseif($order['order_status']=='confirmed')
                                                <span class="badge badge-soft-success fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                            @elseif($order['order_status']=='failed')
                                                <span class="badge badge-danger fz-12">
                                                {{$order['order_status'] == 'failed' ? 'Failed To Deliver' : ''}}
                                            </span>
                                            @elseif($order['order_status']=='delivered')
                                                <span class="badge badge-soft-success fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                            @else
                                                <span class="badge badge-soft-danger fz-12">
                                                {{$order['order_status']}}
                                            </span>
                                            @endif
                                        </td>

                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">
                                                <div class="text-center p-4">
                                                    <img class="mb-3 w-160" src="{{ asset('public/assets/back-end/svg/illustrations/sorry.svg') }}" alt="Image Description">
                                                    <p class="mb-0">{{ \App\CPU\translate('no_earning_history_found') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse

                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive mt-4">
                                <div class="px-4 d-flex justify-content-lg-end">
                                    <!-- Pagination -->
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@push('script')

@endpush
