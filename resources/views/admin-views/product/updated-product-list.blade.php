@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('updated_product_list'))

@push('css_or_js')

@endpush

@section('content')

<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 text-capitalize mb-1 d-flex gap-2">
            <img src="{{asset('/assets/back-end/img/inhouse-product-list.png')}}" alt="">
            {{\App\CPU\translate('update_product')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row gy-2 justify-content-between align-items-center">
                        <div class="col-auto">
                            <h5 class="mb-0">
                                    {{\App\CPU\translate('product_table')}}
                                    <span class="badge badge-soft-dark radius-50 fz-12 ml-1">{{ $pro->total() }}</span>
                            </h5>
                        </div>
                        <div class="col-auto">
                            <!-- Search -->
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                           placeholder="{{\App\CPU\translate('Search Product Name')}}" aria-label="Search orders"
                                           value="{{ $search }}" required>
                                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{\App\CPU\translate('SL')}}</th>
                            <th>{{\App\CPU\translate('Product Name')}}</th>
                            <th>{{\App\CPU\translate('previous_shipping_cost')}}</th>
                            <th>{{\App\CPU\translate('new_shipping_cost')}}</th>
                            <th class="text-center">{{\App\CPU\translate('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($pro as $k=>$p)
                            <tr>
                                <th scope="row">{{$pro->firstItem()+$k}}</th>
                                <td>
                                    <a href="{{route('admin.product.view',[$p['id']])}}" class="title-color hover-c1">
                                        {{\Illuminate\Support\Str::limit($p['name'],20)}}
                                    </a>
                                </td>
                                <td>
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['shipping_cost']))}}
                                </td>
                                <td>
                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['temp_shipping_cost']))}}
                                </td>

                                <td>
                                    <div class="d-flex gap-10 align-items-center justify-content-center">
                                        <button class="btn btn--primary btn-sm"
                                            onclick="update_shipping_status({{$p['id']}},1)">
                                            {{\App\CPU\translate('approved')}}
                                        </button>
                                        <button class="btn btn-danger btn-sm"
                                            onclick="update_shipping_status({{$p['id']}},0)">
                                            {{\App\CPU\translate('deneid')}}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        <!-- Pagination -->
                        {{$pro->links()}}
                    </div>
                </div>

                @if(count($pro)==0)
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
<script>
    function update_shipping_status(product_id,status) {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.product.updated-shipping')}}",
                method: 'POST',
                data: {
                    product_id: product_id,
                    status:status
                },
                success: function (data) {

                    toastr.success('{{\App\CPU\translate('status updated successfully')}}');
                    location.reload();
                }
            });
        }
</script>

@endpush
