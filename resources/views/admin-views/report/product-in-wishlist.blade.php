@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{asset('/assets/back-end/img/seller_sale.png')}}" alt="">
                {{\App\CPU\translate('product_report')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Inlile Menu -->
        @include('admin-views.report.product-report-inline-menu')
        <!-- End Inlile Menu -->

        <div class="row">
            <div class="col-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <form action="" id="form-data" method="GET">
                            <h4 class="mb-3">{{\App\CPU\translate('Filter_Data')}}</h4>
                            <div class="row g-2 align-items-center text-left">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <select
                                        class="js-select2-custom form-control text-ellipsis"
                                        name="seller_id">
                                        <option value="all" {{ $seller_id == 'all' ? 'selected' : '' }}>{{\App\CPU\translate('All')}}</option>
                                        <option value="in_house" {{ $seller_id == 'in_house' ? 'selected' : '' }}>{{\App\CPU\translate('In-House')}}</option>
                                        @foreach(\App\Model\Seller::where(['status'=>'approved'])->get() as $seller)
                                            <option value="{{ $seller['id'] }}" {{ $seller_id == $seller['id'] ? 'selected' : '' }}>
                                                {{$seller['f_name']}} {{$seller['l_name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="">
                                        <select
                                            class="form-control"
                                            name="sort">
                                            <option value="ASC" {{ $sort == 'ASC' ? 'selected' : '' }}>{{\App\CPU\translate('wishlist_sort_by_(low_to_high)')}}</option>
                                            <option value="DESC" {{ $sort == 'DESC' ? 'selected' : '' }}>{{\App\CPU\translate('wishlist_sort_by_(high_to_low)')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-lg-3 text-right text-md-left">
                                    <button type="submit" class="btn btn--primary px-4 px-md-5">
                                        {{\App\CPU\translate('filter')}}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-0">
                        <div class="d-flex flex-wrap w-100 gap-3 align-items-center">
                            <h4 class="mb-0 mr-auto">
                                {{\App\CPU\translate('Total_Products')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">{{ $products->total() }}</span>
                            </h4>
                            <form action="" method="GET">
                                <!-- Search -->
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $seller_id }}" name="seller_id">
                                    <input type="hidden" value="{{ $sort }}" name="sort">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                           placeholder="{{\App\CPU\translate('Search Product Name')}}" aria-label="Search orders" value="{{ $search }}">
                                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                                <!-- End Search -->
                            </form>
                            <div>
                                <button type="button" class="btn btn-outline--primary text-nowrap btn-block" data-toggle="dropdown">
                                    <i class="tio-download-to"></i>
                                    {{ \App\CPU\translate('Export') }}
                                    <i class="tio-chevron-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li><a class="dropdown-item" href="{{route('admin.stock.wishlist-product-export', ['seller_id'=>$seller_id, 'sort'=>$sort, 'search'=>$search])}}">{{\App\CPU\translate('excel')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive" id="products-table">
                        <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                            <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}</th>
                                <th>
                                    {{\App\CPU\translate('Product Name')}}
                                </th>
                                <th>
                                    {{\App\CPU\translate('Date')}}
                                </th>
                                <th class="text-center">
                                    {{\App\CPU\translate('Total in Wishlist')}}
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$data)
                                <tr>
                                    <td>{{$products->firstItem()+$key}}</td>
                                    <td>
                                        <a href="{{route('admin.product.view',[$data['id']])}}">
                                            <span class="media-body title-color hover-c1">
                                                {{\Illuminate\Support\Str::limit($data['name'], 20)}}
                                            </span>
                                        </a>
                                    </td>
                                    <td>{{ date('d M Y', $data['created_at'] ? strtotime($data['created_at']) : null) }}</td>
                                    <td class="text-center">{{ $data->wish_list_count }}</td>
                                </tr>
                            @endforeach
                            @if(count($products)==0)
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center p-4">
                                            <img class="mb-3 w-160" src="{{asset('assets/back-end')}}/svg/illustrations/sorry.svg"
                                                 alt="Image Description">
                                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show')}}</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-center justify-content-md-end">
                            <!-- Pagination -->
                            {!! $products->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Stats -->
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.js-select2-custom').select2();
        });
    </script>
@endpush
