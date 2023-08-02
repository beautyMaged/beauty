@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Review List'))
@section('content')
    <div class="content container-fluid">

        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize">
                <img width="20" src="{{asset('/public/assets/back-end/img/product-review.png')}}" class="mb-1 mr-1" alt="">
                {{\App\CPU\translate('Product_reviews')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="card card-body">
            <div class="row border-bottom pb-3 align-items-center mb-20">
                <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                    <h5 class="text-capitalize mb-0 d-flex gap-1">
                        {{ \App\CPU\translate('Review') }} {{ \App\CPU\translate('Table') }}
                        <span class="badge badge-soft-dark radius-50 fz-12">{{ $reviews->total() }}</span>
                    </h5>
                </div>
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
                                placeholder="{{ \App\CPU\translate('Search by Product or Customer') }}"
                                aria-label="Search orders" value="{{ $search }}" required>
                            <button type="submit" class="btn btn--primary">{{ \App\CPU\translate('search') }}</button>
                        </div>
                    </form>
                    <!-- End Search -->
                </div>
            </div>
            <form action="{{ url()->current() }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product" class="title-color">{{ \App\CPU\translate('choose') }}
                                {{ \App\CPU\translate('product') }}</label>
                            <select class="form-control" name="product_id">
                                <option value="" selected>
                                    --{{ \App\CPU\translate('select_product') }}--</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}"
                                        {{ $product_id == $product->id ? 'selected' : '' }}>
                                        {{ Str::limit($product->name, 20) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer" class="title-color">{{ \App\CPU\translate('choose') }}
                                {{ \App\CPU\translate('customer') }}</label>
                            <select class="form-control" name="customer_id">
                                <option value="" selected>
                                    --{{ \App\CPU\translate('select_customer') }}--</option>
                                @foreach ($customers as $item)
                                    <option value="{{ isset($item->id) ? $item->id : $customer_id }}"
                                        {{ $customer_id != null && $customer_id == $item->id ? 'selected' : '' }}>
                                        {{ Str::limit($item->f_name) }}
                                        {{ Str::limit($item->l_name) }}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">

                            <label for="status" class="title-color">{{ \App\CPU\translate('choose') }}
                                {{ \App\CPU\translate('status') }}</label>
                            <select class="form-control" name="status">
                                <option value="" selected>
                                    --{{ \App\CPU\translate('select_status') }}--</option>
                                <option value="1" {{ $status != null && $status == 1 ? 'selected' : '' }}>
                                    {{ \App\CPU\translate('active') }}</option>
                                <option value="0" {{ $status != null && $status == 0 ? 'selected' : '' }}>
                                    {{ \App\CPU\translate('inactive') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="from" class="title-color">{{ \App\CPU\translate('from') }}</label>
                            <input type="date" name="from" id="from_date" value="{{ $from }}"
                                class="form-control"
                                title="{{ \App\CPU\translate('from') }} {{ \App\CPU\translate('date') }}">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="to" class="title-color">{{ \App\CPU\translate('to') }}</label>
                            <input type="date" name="to" id="to_date" value="{{ $to }}"
                                class="form-control"
                                title="{{ ucfirst(\App\CPU\translate('to')) }} {{ \App\CPU\translate('date') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button id="filter" type="submit" class="btn btn--primary btn-block mt-5 filter">
                                <i class="tio-filter-list nav-icon"></i>{{ \App\CPU\translate('filter') }}
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <button type="button" class="btn btn-outline--primary mt-5" data-toggle="dropdown">
                                <i class="tio-download-to"></i>
                                {{\App\CPU\translate('export')}}
                                <i class="tio-chevron-down"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-menu-right">
                                <li>
                                    <a type="submit" class="dropdown-item" href="{{ route('seller.reviews.export', ['product_id' => $product_id, 'customer_id' => $customer_id, 'status' => $status, 'from' => $from, 'to' => $to]) }}">
                                        {{\App\CPU\translate('Excel')}}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="card mt-20">
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }}">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ \App\CPU\translate('SL') }}</th>
                            <th>{{ \App\CPU\translate('Product') }}</th>
                            <th>{{ \App\CPU\translate('Customer') }}</th>
                            <th>{{ \App\CPU\translate('Rating') }}</th>
                            <th>{{ \App\CPU\translate('Review') }}</th>
                            <th>{{ \App\CPU\translate('date') }}</th>
                            <th class="text-center">{{ \App\CPU\translate('status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviews as $key => $review)
                            @if ($review->product)
                                <tr>
                                    <td>
                                        {{ $reviews->firstItem()+$key }}
                                    </td>
                                    <td>
                                        <a class="title-color hover-c1" href="{{ route('seller.product.view', [$review['product_id']]) }}">
                                            {{ Str::limit($review->product['name'], 25) }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($review->customer)
                                            {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                        @else
                                            <label class="badge badge-soft-danger">{{ \App\CPU\translate('customer_removed') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <label class="badge badge-soft-info mb-0">
                                            <span class="fz-12 d-flex align-items-center gap-1">{{ $review->rating }} <i class="tio-star"></i>
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            {{ $review->comment ? Str::limit($review->comment, 35) : 'No Comment Found' }}
                                        </div>
                                        <div class="gap-1">
                                        @if($review->attachment)
                                            @foreach (json_decode($review->attachment) as $img)
                                                <a class=""
                                                    href="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                    data-lightbox="mygallery">
                                                    <img clsss="p-2" width="60" height="60"
                                                        onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                                        src="{{ asset('storage/app/public/review') }}/{{ $img }}"
                                                        alt="Image">
                                                </a>
                                            @endforeach
                                        @endif
                                        </div>
                                    </td>
                                    <td>{{ date('d M Y', strtotime($review->created_at)) }}</td>
                                    <td>
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input"
                                                onclick="location.href='{{ route('seller.reviews.status', [$review['id'], $review->status ? 0 : 1]) }}'"
                                                class="toggle-switch-input" {{ $review->status ? 'checked' : '' }}>
                                            <span class="switcher_control"></span>
                                        </label>
                                    </td>

                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    <!-- Pagination -->
                    {!! $reviews->links() !!}
                </div>
            </div>
            <!-- End Pagination -->
        </div>
    </div>
@endsection
@push('script_2')
    <script>
        $(document).on('change', '#from_date', function() {
            from_date = $(this).val();
            if (from_date) {
                $("#to_date").prop('required', true);
            }
        });
    </script>
    <script>
        $('#from_date , #to_date').change(function() {
            let fr = $('#from_date').val();
            let to = $('#to_date').val();
            if (fr != '' && to != '') {
                if (fr > to) {
                    $('#from_date').val('');
                    $('#to_date').val('');
                    toastr.error('Invalid date range!', Error, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            }

        })
    </script>
@endpush
