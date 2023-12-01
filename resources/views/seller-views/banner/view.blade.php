@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('marketing_banner'))
@section('content')

    <style>
        .select2-selection__choice {
            color: black !important;
        }

        .select2-selection__choice__remove {
            margin-right: 0.25rem !important;
        }
    </style>
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assets/back-end/img/banner.png') }}" alt="">
                {{ \App\CPU\translate('banner') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row pb-4 d--none" id="main-banner"
            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 text-capitalize">{{ \App\CPU\translate('banner_form') }}</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('seller.banner.store') }}" method="post" enctype="multipart/form-data"
                            class="banner_form">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    @php
                                        $products = auth()->user()->products;
                                        $categories = auth()
                                            ->user()
                                            ->categories();
                                    @endphp
                                    <div class="form-group">
                                        <label for="title"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('title') }}</label>
                                        <input type="text" name="title" class="form-control" id="title">
                                    </div>
                                    <div class="form-group">
                                        <label for="description"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('description') }}</label>
                                        <input type="text" name="description" class="form-control" id="description">
                                    </div>

                                    <div class="form-group">
                                        <label for="resource_type"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('resource_type') }}</label>
                                        <select id="resource_type" onchange="display_data(this.value)"
                                            class="select2-no-search form-control w-100" name="resource_type" required>
                                            <option value="home">{{ \App\CPU\translate('Home') }}</option>
                                            {{-- @if (!$products->isEmpty())
                                                <option value="product">{{ \App\CPU\translate('Product') }}</option>
                                            @endif --}}
                                            @if (!$categories->isEmpty())
                                                <option value="category">{{ \App\CPU\translate('category') }}</option>
                                            @endif
                                            {{--                                            <option value="shop">{{ \App\CPU\translate('Shop')}}</option> --}}
                                            {{--                                            <option value="brand">{{ \App\CPU\translate('Brand')}}</option> --}}
                                        </select>
                                    </div>

                                    <div class="form-group d--none" id="home-positions">
                                        <label for="banner_type_home"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('banner_type') }}</label>
                                        <select id="banner_type_home"
                                            class="select2-no-search form-control w-100 banner_type"
                                            name="home_banner_position" required>
                                            @foreach (config('services.banner.positions.home') as $position)
                                                <option value="{{ $position['name'] }}">
                                                    {{ \App\CPU\translate($position['name']) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group d--none" id="category-positions">
                                        <label for="banner_type_category"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('banner_type') }}</label>
                                        <select id="banner_type_category"
                                            class="select2-no-search form-control w-100 banner_type"
                                            name="category_banner_position" required>
                                            @foreach (config('services.banner.positions.category') as $position)
                                                <option value="{{ $position['name'] }}">
                                                    {{ \App\CPU\translate($position['name']) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="form-group " id="resource-product">
                                        <label for="product_id"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('product') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="product_id">
                                            @foreach ($products as $product)
                                                <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="form-group d--none" id="resource-category">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('category') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category['id'] }}">
                                                    {{ $category->translations[0]->value ?? $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="form-group d--none" id="resource-shop">
                                        <label for="shop_id" class="title-color">{{ \App\CPU\translate('shop') }}</label>
                                        <select class="w-100 js-example-responsive form-control" name="shop_id">
                                            @foreach (\App\Model\Shop::active()->get() as $shop)
                                                <option value="{{ $shop['id'] }}">{{ $shop['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- <div class="form-group  d--none" id="resource-brand">
                                        <label for="brand_id"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('brand') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="brand_id">
                                            @foreach (\App\Model\Brand::all() as $brand)
                                                <option value="{{ $brand['id'] }}">{{ $brand['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- <div class="form-group">
                                        <label for="name"
                                               class="title-color text-capitalize">{{ \App\CPU\translate('banner_URL')}}</label>
                                        <input type="text" name="url" class="form-control" id="url" required>
                                    </div> --}}
                                    <div class="form-group d-flex gap-2">
                                        <div class="w-50">
                                            <label for="start_at"
                                                class="title-color text-capitalize">{{ \App\CPU\translate('start_at') }}</label>
                                            <input type="datetime-local" name="start_at" class="form-control" id="start_at"
                                                required>
                                        </div>
                                        <div class="w-50">
                                            <label for="end_at"
                                                class="title-color text-capitalize">{{ \App\CPU\translate('end_at') }}</label>
                                            <input type="datetime-local" name="end_at" class="form-control" id="end_at"
                                                required>
                                        </div>
                                    </div>

                                    <div class="form-group" id="banner-target">
                                        <label for="banner-target-input"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('product with banner') }}</label>
                                        <select onchange="banner_target(this.value)" id="banner-target-input"
                                            class="select2-no-search form-control w-100" name="target_type">
                                            <option value="all">{{ \App\CPU\translate('All Products') }}</option>
                                            <option value="products">{{ \App\CPU\translate('chose product') }}</option>
                                            {{-- <option value="home">{{ \App\CPU\translate('Home') }}</option> --}}
                                        </select>
                                    </div>

                                    <div class="form-group d--none" id="banner-target-products">
                                        <select id="banner-target-products-input"
                                            class="select2-multiple form-control w-100" name="target[]">
                                            @foreach ($products as $product)
                                                <option value="{{ $product['id'] }}">{{ $product['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-end">
                                    <div>
                                        <center class="mb-30 mx-auto  rat_4_1">
                                            <img class="ratio-4:1" id="mbImageviewer"
                                                src="{{ asset('assets/front-end/img/placeholder.png') }}"
                                                alt="banner image" />
                                        </center>
                                        <center class="mb-30 mx-auto  rat_3_5_1">
                                            <img class="ratio-3.5:1" id="mbImageviewer"
                                                src="{{ asset('assets/front-end/img/3.5-1.png') }}" alt="banner image" />
                                        </center>
                                        <center class="mb-30 mx-auto rat_2_1">
                                            <img class="ratio-2:1" id="mbImageviewer"
                                                src="{{ asset('assets/front-end/img/2-1.png') }}" alt="banner image" />
                                        </center>
                                        <label for="name"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('Image') }}</label>
                                        <span class="text-info rat_4_1">( {{ \App\CPU\translate('ratio') }} 4:1 )</span>
                                        <span class="text-info rat_2_1">( {{ \App\CPU\translate('ratio') }} 2:1 )</span>
                                        <span class="text-info rat_3_5_1">( {{ \App\CPU\translate('ratio') }} 2:1
                                            )</span>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="mbimageFileUploader"
                                                class="custom-file-input" accept="image/*" required>
                                            <label class="custom-file-label title-color"
                                                for="mbimageFileUploader">{{ \App\CPU\translate('choose') }}
                                                {{ \App\CPU\translate('file') }}</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 d-flex justify-content-end flex-wrap gap-10">
                                    <button class="btn btn-secondary cancel px-4"
                                        type="reset">{{ \App\CPU\translate('reset') }}</button>
                                    <button id="add" type="submit"
                                        class="btn btn--primary px-4">{{ \App\CPU\translate('save') }}</button>
                                    <button id="update"
                                        class="btn btn--primary d--none text-white">{{ \App\CPU\translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="banner-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="row align-items-center">
                            <div class="col-md-4 col-lg-6 mb-2 mb-md-0">
                                <h5 class="mb-0 text-capitalize d-flex gap-2">
                                    {{ \App\CPU\translate('banner_table') }}
                                    <span class="badge badge-soft-dark radius-50 fz-12">{{ $banners->total() }}</span>
                                </h5>
                            </div>
                            <div class="col-md-8 col-lg-6">
                                <div
                                    class="d-flex align-items-center justify-content-md-end flex-wrap flex-sm-nowrap gap-2">
                                    <!-- Search -->
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group input-group-merge input-group-custom">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch_" type="search" name="search"
                                                class="form-control"
                                                placeholder="{{ \App\CPU\translate('Search_by_Banner_Type') }}"
                                                aria-label="Search orders" value="{{ $search }}">
                                            <button type="submit" class="btn btn--primary">
                                                {{ \App\CPU\translate('Search') }}
                                            </button>
                                        </div>
                                    </form>
                                    <!-- End Search -->

                                    <div id="banner-btn">
                                        <button id="main-banner-add" class="btn btn--primary text-nowrap">
                                            <i class="tio-add"></i>
                                            {{ \App\CPU\translate('add_banner') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="columnSearchDatatable"
                            style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                            <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th class="pl-xl-5">{{ \App\CPU\translate('SL') }}</th>
                                    <th> {{ \App\CPU\translate('Title') }}</th>
                                    <th> {{ \App\CPU\translate('Description') }}</th>
                                    <th> {{ \App\CPU\translate('resource_type') }}</th>
                                    <th>{{ \App\CPU\translate('banner_type') }}</th>
                                    <th>{{ \App\CPU\translate('image') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('published') }}</th>
                                    <th class="text-center">{{ \App\CPU\translate('action') }}</th>
                                </tr>
                            </thead>
                            @foreach ($banners as $key => $banner)
                                <tbody>
                                    <tr id="data-{{ $banner->id }}">
                                        <td class="pl-xl-5">{{ $banners->firstItem() + $key }}</td>
                                        <td class="pl-xl-5">{{ $banner->title }}</td>
                                        <td class="pl-xl-5">{{ $banner->description }}</td>
                                        <td class="pl-xl-5">
                                            <a target="_blank"
                                                href="{{ $banner->category ? Str::finish(url('products'), '?') . Arr::query(['data_from' => 'category', 'id' => $banner->category->id]) : url('') }}">
                                                {{ $banner->category ? $banner->category->translations[0]->value ?? $banner->category->name : \App\CPU\translate('Home') }}
                                            </a>
                                        </td>
                                        <td>{{ \App\CPU\translate(str_replace('_', ' ', $banner->banner_type)) }}</td>
                                        <td>
                                            <img class="ratio-4:1" width="80"
                                                onerror="this.onerror=null;this.src='{{ asset('assets/front-end/img/placeholder.png') }}'"
                                                src="{{ asset('storage/banner') }}/{{ $banner['photo'] }}">
                                        </td>
                                        <td>
                                            @if ($banner->published == 0)
                                                <label class="badge badge-soft-warning">معلق</label>
                                            @elseif($banner->published == 1)
                                                <label class="badge badge-soft-success">موافقة</label>
                                            @else
                                                <label class="badge badge-soft-danger">رفض</label>
                                            @endif

                                        </td>
                                        <td>
                                            <div class="d-flex gap-10 justify-content-center">
                                                <a target="_blank"
                                                    style="{{ $banner->published == 1 ? '' : 'display: none;' }}"
                                                    class="view-banner btn btn-outline--primary btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('View') }}"
                                                    href="{{ Str::finish(url('products'), '?') . Arr::query(['data_from' => 'banner', 'id' => $banner['id']]) }}">
                                                    <i class="tio-invisible"></i>
                                                </a>
                                                <a class="btn btn-outline--primary btn-sm cursor-pointer edit"
                                                    title="{{ \App\CPU\translate('Edit') }}"
                                                    href="{{ route('seller.banner.edit', [$banner['id']]) }}">
                                                    <i class="tio-edit"></i>
                                                </a>
                                                <a class="btn btn-outline-danger btn-sm cursor-pointer delete"
                                                    title="{{ \App\CPU\translate('Delete') }}" id="{{ $banner['id'] }}">
                                                    <i class="tio-delete"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            <!-- Pagination -->
                            {{ $banners->links() }}
                        </div>
                    </div>

                    @if (count($banners) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3 w-160" src="{{ asset('assets/back-end') }}/svg/illustrations/sorry.svg"
                                alt="Image Description">
                            <p class="mb-0">{{ \App\CPU\translate('No_data_to_show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            // dir: "rtl",
            width: 'resolve',
        });

        $(".select2-no-search").select2({
            minimumResultsForSearch: -1,
            width: 'resolve',
        });

        $(".select2-multiple").select2({
            multiple: true,
            tags: true,
            width: 'resolve',
            placeholder: '{{ \App\CPU\translate('chose product') }}'
        });
        if ($('.select2-multiple').hasClass("select2-hidden-accessible")) {
            $('.select2-multiple').val(null).trigger('change');
        }

        $('.rat_3_5_1').hide();
        $('.rat_4_1').hide();

        function banner_target(data) {
            if (data == 'products')
                $('#banner-target-products').show()
            else
                $('#banner-target-products').hide()

        }

        $('#resource_type').val(null).trigger('change')
        $('#banner-target-input').trigger('change')

        function display_data(data) {
            let first_data = $('select.banner_type').val();

            // $('#resource-product').hide()
            // $('#resource-brand').hide()
            // $('#resource-category').hide()
            // $('#resource-shop').hide()
            $('.rat_2_1').hide();
            $('.rat_3_5_1').hide();
            $('.rat_4_1').hide();



            // if (data === 'product') {
            //     $('#resource-product').show()
            //     if (first_data === 'Main Banner') {
            //         $('.rat_2_1').show();
            //         $('.main_title_div').show();

            //     } else if (first_data === 'Main Section Banner' || first_data === 'Footer Banner') {
            //         $('.rat_4_1').show();
            //         $('.main_title_div').hide();

            //     }

            // }
            // else
            // if (data === 'brand') {
            //     $('#resource-brand').show()
            // }
            // else
            if (data === 'category') {
                $('#home-positions').hide()
                $('#resource-category').show()
                $('#category-positions').show()
                if (first_data === 'Main Banner') {
                    $('.rat_2_1').show();
                    $('.main_title_div').show();
                } else if (first_data === 'Main Section Banner') {
                    $('.rat_3_5_1').show();
                    $('.main_title_div').hide();

                } else {
                    $('.rat_2_1').show();
                    $('.main_title_div').hide();

                }
            } else
            if (data === 'home') {
                $('#resource-category').hide()
                $('#category-positions').hide()

                $('#home-positions').show()
            }
            // else
            // if (data === 'shop') {
            //     $('#resource-shop').show()
            // }
        }
    </script>
    <script>
        function mbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#mbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#mbimageFileUploader").change(function() {
            mbimagereadURL(this);
        });

        function fbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#fbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#fbimageFileUploader").change(function() {
            fbimagereadURL(this);
        });

        function pbimagereadURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#pbImageviewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#pbimageFileUploader").change(function() {
            pbimagereadURL(this);
        });
    </script>
    <script>
        $('#main-banner-add').on('click', function() {
            $('#main-banner').show();
        });

        $('.cancel').on('click', function() {
            $('.banner_form').attr('action', "{{ route('seller.banner.store') }}");
            $('#main-banner').hide();
        });

        $(document).on('click', '.delete', function() {
            var id = $(this).attr("id");
            Swal.fire({
                title: "{{ \App\CPU\translate('Are_you_sure_delete_this_banner') }}?",
                text: "{{ \App\CPU\translate('You_will_not_be_able_to_revert_this') }}!",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ \App\CPU\translate('Yes') }}, {{ \App\CPU\translate('delete_it') }}!',
                type: 'warning',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('seller.banner.delete') }}",
                        method: 'POST',
                        data: {
                            id: id
                        },
                        success: function(response) {
                            console.log(response)
                            toastr.success(
                                '{{ \App\CPU\translate('Banner_deleted_successfully') }}');
                            $('#data-' + id).hide();
                        }
                    });
                }
            })
        });
    </script>
    <!-- Page level plugins -->
@endpush
