@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Product List'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                <img src="{{ asset('/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                @if ($type == 'in_house')
                    {{ \App\CPU\translate('In-House_Product_List') }}
                @elseif($type == 'seller')
                    {{ \App\CPU\translate('Seller_Product_List') }}
                @endif
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $pro->total() }}</span>
            </h2>
        </div>
        <!-- End Page Title -->

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-custom input-group-merge">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ \App\CPU\translate('Search Product Name') }}"
                                            aria-label="Search orders" value="{{ $search }}" required>
                                        <input type="hidden" value="{{ $request_status }}" name="status">
                                        <button type="submit"
                                            class="btn btn--primary">{{ \App\CPU\translate('search') }}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                            <div class="col-lg-8 mt-3 mt-lg-0 d-flex flex-wrap gap-3 justify-content-lg-end">
                                @if ($type == 'in_house')
                                    <div>
                                        <button type="button" class="btn btn-outline--primary" data-toggle="dropdown">
                                            <i class="tio-download-to"></i>
                                            {{ \App\CPU\translate('Export') }}
                                            <i class="tio-chevron-down"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                            <li><a class="dropdown-item"
                                                    href="{{ route('admin.product.export-excel', ['in_house', '']) }}">{{ \App\CPU\translate('Excel') }}</a>
                                            </li>
                                            <div class="dropdown-divider"></div>
                                        </ul>
                                    </div>
                                    <a href="{{ route('admin.product.stock-limit-list', ['in_house']) }}"
                                        class="btn btn-info">
                                        <span class="text">{{ \App\CPU\translate('Limited Sotcks') }}</span>
                                    </a>
                                @endif
                                @if (!isset($request_status))
                                    <a href="{{ route('admin.product.add-new') }}" class="btn btn--primary">
                                        <i class="tio-add"></i>
                                        <span class="text">{{ \App\CPU\translate('Add_New_Product') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{ \App\CPU\translate('SL') }}</th>
                                    <th>{{ \App\CPU\translate('Product Name') }}</th>
                                    {{--                                <th class="text-right">{{\App\CPU\translate('Product Type')}}</th> --}}
                                    <th class="text-right">{{ \App\CPU\translate('selling_price') }}</th>
                                    <th class="text-right">{{ \App\CPU\translate('unit_price') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('Show_as_featured') }}</th>
                                    <th class="text-center">
                                        {{ \App\CPU\translate('Active') }}{{ \App\CPU\translate('status') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('collection') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('category') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('sub_category') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('sub_sub_category') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('sub_sub_sub_category') }}</th>

                                    <th class="text-center">{{ \App\CPU\translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pro as $k => $p)
                                    @php
                                        $product_category = json_decode($p->category_ids);
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $pro->firstItem() + $k }}</th>
                                        <td>
                                            <a href="{{ route('admin.product.view', [$p['id']]) }}"
                                                class="media align-items-center gap-2">
                                                <img src="{{ \App\CPU\ProductManager::product_image_path('thumbnail') }}/{{ $p['thumbnail'] }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('/assets/back-end/img/brand-logo.png') }}'"class="avatar border"
                                                    alt="">
                                                <span class="media-body title-color hover-c1">
                                                    {{ \Illuminate\Support\Str::limit($p['name'], 20) }}
                                                </span>
                                            </a>
                                        </td>
                                        {{--                                <td class="text-right"> --}}
                                        {{--                                    {{\App\CPU\translate(str_replace('_',' ',$p['product_type']))}} --}}
                                        {{--                                </td> --}}
                                        <td class="text-right" dir="{{ session('direction') }}">
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['purchase_price'])) }}
                                        </td>
                                        <td class="text-right">
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price'])) }}
                                        </td>
                                        <td class="text-center">
                                            <label class="mx-auto switcher">
                                                <input class="switcher_input" type="checkbox"
                                                    onclick="featured_status('{{ $p['id'] }}')"
                                                    {{ $p->featured == 1 ? 'checked' : '' }}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <label class="mx-auto switcher">
                                                <input type="checkbox" class="status switcher_input"
                                                    id="{{ $p['id'] }}" {{ $p->status == 1 ? 'checked' : '' }}>
                                                <span class="switcher_control"></span>
                                            </label>
                                        </td>
                                        <td>
                                            {{ $p['collection'] ?? \App\CPU\translate('not_available') }}
                                        </td>
                                        <td>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                id="category_{{ $p['id'] }}" name="category_id"
                                                onchange="getRequest('{{ url('/') }}/admin/product/get-categories?parent_id='+this.value,'sub_category_{{ $p['id'] }}','select')">
                                                {!! $categories[$k] !!}
                                            </select>
                                        </td>
                                        <td>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                id="sub_category_{{ $p['id'] }}" name="sub_category_id"
                                                data-id="{{ count($product_category) >= 2 ? $product_category[1]->id : '' }}"
                                                onchange="getRequest('{{ url('/') }}/admin/product/get-categories?parent_id='+this.value,'sub_sub_category_{{ $p['id'] }}','select')">
                                                {!! $sub_categories[$k] !!}
                                            </select>
                                        </td>
                                        <td>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                id="sub_sub_category_{{ $p['id'] }}" name="sub_sub_category_id"
                                                data-id="{{ count($product_category) >= 3 ? $product_category[2]->id : '' }}"
                                                onchange="getRequest('{{ url('/') }}/admin/product/get-categories?parent_id='+this.value,'sub_sub_sub_category_{{ $p['id'] }}','select')">
                                                {!! $sub_sub_categories[$k] !!}
                                            </select>
                                        </td>
                                        <td>
                                            <select
                                                class="js-example-basic-multiple js-states js-example-responsive form-control"
                                                id="sub_sub_sub_category_{{ $p['id'] }}"
                                                name="sub_sub_sub_category_id"
                                                data-id="{{ count($product_category) >= 4 ? $product_category[3]->id : '' }}"
                                                onchange="update({{ $p['id'] }})">
                                                {!! $sub_sub_sub_categories[$k] !!}
                                            </select>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info btn-sm square-btn"
                                                    title="{{ \App\CPU\translate('barcode') }}"
                                                    href="{{ route('admin.product.barcode', [$p['id']]) }}">
                                                    <i class="tio-barcode"></i>
                                                </a>
                                                <a class="btn btn-outline-info btn-sm square-btn" title="View"
                                                    href="{{ route('admin.product.view', [$p['id']]) }}">
                                                    <i class="tio-invisible"></i>
                                                </a>
                                                <a class="btn btn-outline--primary btn-sm square-btn"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('admin.product.edit', [$p['id']]) }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm square-btn" href="javascript:"
                                                    title="{{ \App\CPU\translate('Delete') }}"
                                                    onclick="form_alert('product-{{ $p['id'] }}','Want to delete this item ?')">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                            <form action="{{ route('admin.product.delete', [$p['id']]) }}" method="post"
                                                id="product-{{ $p['id'] }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{ $pro->links() }}
                        </div>
                    </div>

                    @if (count($pro) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{ asset('assets/back-end') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        const update = id => {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: `{{ route('admin.product.update-categories', '') }}/${id}`,
                dataType: 'json',
                data: {
                    category_id: $(`#category_${id}`).val(),
                    sub_category_id: $(`#sub_category_${id}`).val(),
                    sub_sub_category_id: $(`#sub_sub_category_${id}`).val(),
                    sub_sub_sub_category_id: $(`#sub_sub_sub_category_${id}`).val(),
                },
                success: function(data) {

                },
            });
        }
        // Call the dataTables jQuery plugin
        $(document).ready(function() {
            $('#dataTable').DataTable();
            // setTimeout(function () {
            //     let category = $("#category_id").val();
            //     let sub_category = $("#sub-category-select").attr("data-id");
            //     let sub_sub_category = $("#sub-sub-category-select").attr("data-id");
            //     getRequest('{{ url('/') }}/admin/product/get-categories?parent_id=' + category + '&sub_category=' + sub_category, 'sub-category-select', 'select');
            //     getRequest('{{ url('/') }}/admin/product/get-categories?parent_id=' + sub_category + '&sub_category=' + sub_sub_category, 'sub-sub-category-select', 'select');
            // }, 100)
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        function getRequest(route, id, type) {
            if (route.includes('parent_id=0'))
                $('#' + id).empty().append(
                    `<option value="0" disabled selected>---{{ \App\CPU\translate('Select') }}---</option>`).trigger(
                    'change')
            else
                $.get({
                    url: route,
                    dataType: 'json',
                    success: function(data) {
                        if (type == 'select')
                            $('#' + id).empty().append(data.select_tag).trigger('change')
                    },
                });
        }

        $(document).on('change', '.status', function() {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true)
                var status = 1;
            else if ($(this).prop("checked") == false)
                var status = 0;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.product.status-update') }}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(data) {
                    if (data.success == true) {
                        toastr.success('{{ \App\CPU\translate('Status updated successfully') }}');
                    } else if (data.success == false) {
                        toastr.error(
                            '{{ \App\CPU\translate('Status updated failed. Product must be approved') }}'
                        );
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                }
            });
        });

        function featured_status(id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{ route('admin.product.featured-status') }}",
                method: 'POST',
                data: {
                    id: id
                },
                success: function() {
                    toastr.success('{{ \App\CPU\translate('Featured status updated successfully') }}');
                }
            });
        }
    </script>
@endpush
