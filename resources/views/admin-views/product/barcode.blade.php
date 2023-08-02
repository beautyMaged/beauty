@extends('layouts.back-end.app')
@section('title', $product->name . ' barcode ' . date('Y/m/d'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ asset('assets/back-end') }}/css/barcode.css" />
@endpush
@section('content')
    <div class="row m-2 show-div pt-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                    <img src="{{asset('/assets/back-end/img/inhouse-product-list.png')}}" alt="">
                    {{\App\CPU\translate('generate_barcode')}}
                </h2>
            </div>
            <!-- End Page Title -->

            <div class="card">
                <div class="py-4">
                    <div class="table-responsive">
                        <table style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('code') }}</th>
                                    <th>{{ \App\CPU\translate('name') }}</th>
                                    <th>{{ \App\CPU\translate('quantity') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <form action="{{ url()->current() }}" method="GET">

                                        <th>
                                            @if ($product->code)
                                                <span>
                                                    {{$product->code}}
                                                </span>

                                            @else

                                                <a class="title-color hover-c1" href="{{route('admin.product.edit',[$product['id']])}}">
                                                    {{ \App\CPU\translate('update_your_product_code') }}
                                                </a>

                                            @endif
                                            </th>
                                        <th>{{ Str::limit($product->name, 20) }}</th>
                                        <th>
                                            <input id="limit" class="form-control" type="number" name="limit" min="1"
                                                value="{{ $limit }}">
                                            <span
                                                class="text-danger mt-1 d-block">{{ \App\CPU\translate('maximum_quantity_270') }}</span>
                                        </th>

                                        <th>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button class="btn btn-outline-info"
                                                    type="submit">{{ \App\CPU\translate('generate_barcode') }}</button>
                                                <a href="{{ route('admin.product.barcode', [$product['id']]) }}"
                                                    class="btn btn-outline-danger">{{ \App\CPU\translate('reset') }}</a>
                                                <button type="button" id="print_bar" onclick="printDiv('printarea')"
                                                    class="btn btn-outline--primary ">{{ \App\CPU\translate('print') }}</button>
                                            </div>
                                        </th>
                                    </form>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mt-5 p-4">
            <h1 class="style-one-br show-div2">
                {{ \App\CPU\translate("This page is for A4 size page printer, so it won't be visible in smaller devices") }}.
            </h1>
        </div>
    </div>

    <div id="printarea" class="show-div pb-5">
        @if ($limit)
            <div class="barcodea4">
                @for ($i = 0; $i < $limit; $i++)
                    @if ($i % 27 == 0 && $i != 0)
            </div>
            <div class="barcodea4">
        @endif
        <div class="item style24">
            <span
                class="barcode_site text-capitalize">{{ \App\Model\BusinessSetting::where('type', 'company_name')->first()->value }}</span>
            <span class="barcode_name text-capitalize">{{ Str::limit($product->name, 20) }}</span>
            <div class="barcode_price text-capitalize">
                {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($product->unit_price)) }}
            </div>

            @if ($product->code !== null)
                <div class="barcode_image">{!! DNS1D::getBarcodeHTML($product->code, 'C128') !!}</div>
                <div class="barcode_code text-capitalize">{{ \App\CPU\translate('code') }} : {{ $product->code }}</div>
            @else
                <p class="text-danger">{{ \App\CPU\translate('please_update_product_code') }}</p>
            @endif
        </div>
        @endfor
    </div>
    @endif
    </div>
@endsection
@push('script_2')
{{--    <script src={{ asset('assets/admin/js/global.js') }}></script>--}}
    <script>
        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
    </script>
@endpush
