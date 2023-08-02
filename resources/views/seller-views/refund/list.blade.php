@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('refund_list'))

@section('content')
<div class="content container-fluid">
    <!-- Page Header -->
    <div class="">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
            <!-- Page Title -->
            <div class="">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{asset('/public/assets/back-end/img/refund-request-list.png')}}" alt="">
                    {{\App\CPU\translate('refund_request_list')}}
                    <span class="badge badge-soft-dark radius-50">{{$refund_list->total()}}</span>
                </h2>
            </div>
            <!-- End Page Title -->

            <div>
                <i class="tio-shopping-cart title-color fz-30"></i>
            </div>
        </div>
        <!-- End Row -->
    </div>
    <!-- End Page Header -->

    <!-- Card -->
    <div class="card">
        <!-- Header -->
        <div class="card-header">
            <div class="flex-between justify-content-between align-items-center flex-grow-1">
                <div class="col-12 col-md-4">
                    <form action="{{ url()->current() }}" method="GET">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{\App\CPU\translate('Search_by_order_id_or_refund_id')}}" aria-label="Search orders" value="{{ $search }}"
                                   required>
                            <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                        </div>
                        <!-- End Search -->
                    </form>
                </div>
            </div>
            <!-- End Row -->
        </div>
        <!-- End Header -->

        <!-- Table -->
        <div class="table-responsive datatable-custom">
            <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th class="">
                            {{\App\CPU\translate('SL')}}
                        </th>
                        <th>{{\App\CPU\translate('order_ID')}} </th>
                        <th>{{\App\CPU\translate('product_Info')}}</th>
                        <th>{{\App\CPU\translate('customer_Info')}}</th>
                        <th>{{\App\CPU\translate('Total_Amount')}}</th>
                        <th>{{\App\CPU\translate('Order_Status')}}</th>
                        <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($refund_list as $key=>$refund)
                    <tr>
                        <td>
                            {{$refund_list->firstItem()+$key}}
                        </td>
                        <td>
                            <a class="title-color hover-c1" href="{{route('seller.orders.details',[$refund->order_id])}}">
                                {{$refund->order_id}}
                            </a>
                        </td>
                        <td>
                            @if ($refund->product!=null)
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{route('seller.product.view',[$refund->product->id])}}">
                                        <img onerror="this.src='{{asset('/public/assets/back-end/img/brand-logo.png')}}'"
                                             src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{ $refund->product->thumbnail }}"
                                             class="avatar border" alt="">
                                    </a>
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{route('seller.product.view',[$refund->product->id])}}" class="title-color font-weight-bold hover-c1">
                                            {{\Illuminate\Support\Str::limit($refund->product->name,35)}}
                                        </a>
                                        <span class="fz-12">Qty : 3</span>
                                    </div>
                                </div>
                            @else
                                {{\App\CPU\translate('product_name_not_found')}}
                            @endif

                        </td>
                        <td>
                            @if ($refund->customer !=null)
                                <div class="d-flex flex-column gap-1">
                                    <a href="javascript:void(0)" class="title-color font-weight-bold hover-c1">
                                        {{$refund->customer->f_name. ' '.$refund->customer->l_name}}
                                    </a>
                                    <a href="tel:{{$refund->customer->phone}}" class="title-color hover-c1 fz-12">{{$refund->customer->phone}}</a>
                                </div>
                            @else
                                <a href="#" class="title-color hover-c1">
                                    {{\App\CPU\translate('customer_not_found')}}
                                </a>
                            @endif
                        </td>
                        <td>
                            {{\App\CPU\Helpers::currency_converter($refund->amount)}}
                        </td>
                        <td>
                            {{\App\CPU\translate($refund->status)}}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <a  class="btn btn--primary btn-sm square-btn"
                                    title="{{\App\CPU\translate('view')}}"
                                    href="{{route('seller.refund.details',['id'=>$refund['id']])}}">
                                    <i class="tio-invisible"></i>
                                </a>
                            </div>
                        </td>
                    </tr>

                @endforeach
                </tbody>
            </table>
        </div>
        <!-- End Table -->

        <div class="table-responsive mt-4">
            <div class="px-4 d-flex justify-content-lg-end">
                <!-- Pagination -->
                {!! $refund_list->links() !!}
            </div>
        </div>

        @if(count($refund_list)==0)
            <div class="text-center p-4">
                <img class="mb-3 __w-7rem" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
            </div>
        @endif
        <!-- End Footer -->
    </div>
    <!-- End Card -->
</div>
@endsection

@push('script_2')

@endpush
