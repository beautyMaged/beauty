@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Seller List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{asset('/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{\App\CPU\translate('seller_list')}}
                <span class="badge badge-soft-dark radius-50 fz-12">{{ $sellers->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row justify-content-between align-items-center">
                            <div class="col-sm-8 col-md-6 col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-custom">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('Search by Name or Phone or Email')}}" aria-label="Search orders" value="{{ $search }}">
                                        <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{route('admin.sellers.seller-add')}}" type="button" class="btn btn--primary text-nowrap">
                                        <i class="tio-add"></i>
                                        {{\App\CPU\translate('add_new_seller')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>{{\App\CPU\translate('shop_name')}}</th>
                                <th>{{\App\CPU\translate('seller_name')}}</th>
                                <th>{{\App\CPU\translate('contact_info')}}</th>
                                <th>{{\App\CPU\translate('status')}}</th>
                                <th class="text-center">{{\App\CPU\translate('total_products')}}</th>
                                <th class="text-center">{{\App\CPU\translate('total_orders')}}</th>
                                <th class="text-center">{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($sellers as $key=>$seller)
                                <tr>
                                    <td>{{$sellers->firstItem()+$key}}</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-10 w-max-content">
                                            <img width="50"
                                            class="aspect-1 rounded"
                                                onerror="this.onerror=null;this.src='{{asset('assets/back-end/img/400x400/img2.jpg')}}'"
                                                src="{{asset('storage/shop')}}/{{$seller->shop->image}}"
                                                alt="">
                                            <div>
                                                <a class="title-color" href="{{ route('admin.sellers.view', ['id' => $seller->id]) }}">{{ \Str::limit($seller->shop->name, 20)}}</a>
                                                <br>
                                                <span class="text-danger">
                                                    @if($seller->shop->temporary_close)
                                                        {{ \App\CPU\translate('temporary_closed') }}
                                                    @elseif($seller->shop->vacation_status && $current_date >= date('Y-m-d', strtotime($seller->shop->vacation_start_date)) && $current_date <= date('Y-m-d', strtotime($seller->shop->vacation_end_date)))
                                                        {{ \App\CPU\translate('on_vacation') }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a title="{{\App\CPU\translate('View')}}"
                                           class="title-color"
                                           href="{{route('admin.sellers.view',$seller->id)}}">
                                            {{$seller->f_name}} {{$seller->l_name}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <strong><a class="title-color hover-c1" href="mailto:{{$seller->email}}">{{$seller->email}}</a></strong>
                                        </div>
                                        <a class="title-color hover-c1" href="tel:{{$seller->phone}}">{{$seller->phone}}</a>
                                    </td>
                                    <td>
                                        {!! $seller->status=='approved'?'<label class="badge badge-success">'.\App\CPU\translate('Active').'</label>':'<label class="badge badge-danger">'.\App\CPU\translate('In-Active').'</label>' !!}
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.sellers.product-list',[$seller['id']])}}"
                                           class="btn text--primary bg-soft--primary font-weight-bold px-3 py-1 mb-0 fz-12">
                                            {{$seller->product->count()}}
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{route('admin.sellers.order-list',[$seller['id']])}}"
                                            class="btn text-info bg-soft-info font-weight-bold px-3 py-1 fz-12 mb-0">
                                            {{$seller->orders->where('seller_is','seller')->where('order_type','default_type')->count()}}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a title="{{\App\CPU\translate('View')}}"
                                                class="btn btn-outline-info btn-sm square-btn"
                                                href="{{route('admin.sellers.view',$seller->id)}}">
                                                <i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            <!-- Pagination -->
                            {!! $sellers->links() !!}
                        </div>
                    </div>

                    @if(count($sellers)==0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
