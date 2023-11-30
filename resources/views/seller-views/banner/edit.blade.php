@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('Banner'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

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
                {{ \App\CPU\translate('banner_update_form') }}
            </h2>
        </div>
        <!-- End Page Title -->

        <!-- Content Row -->
        <div class="row" style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('seller.banner.update', [$banner['id']]) }}" method="post"
                            enctype="multipart/form-data" class="banner_form">
                            @csrf
                            @method('put')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    @php
                                        $products = auth()->user()->products;
                                        $target_products = $banner
                                            ->products()
                                            ->select('id')
                                            ->get()
                                            ->pluck('id');
                                        $categories = auth()
                                            ->user()
                                            ->categories();
                                    @endphp
                                    <div class="form-group">
                                        <label for="title"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('title') }}</label>
                                        <input value="{{ $banner->title }}" type="text" name="title"
                                            class="form-control" id="title">
                                    </div>
                                    <div class="form-group">
                                        <label for="description"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('description') }}</label>
                                        <input value="{{ $banner->description }}" type="text" name="description"
                                            class="form-control" id="description">
                                    </div>

                                    <div class="form-group">
                                        <label for="resource_type"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('resource_type') }}</label>
                                        <select id="resource_type" onchange="display_data(this.value)"
                                            class="select2-no-search form-control w-100" name="resource_type" required>
                                            <option {{ !$banner->category_id ? 'selected' : '' }} value="home">
                                                {{ \App\CPU\translate('Home') }}</option>
                                            {{-- @if (!$products->isEmpty())
                                                <option value="product">{{ \App\CPU\translate('Product') }}</option>
                                            @endif --}}
                                            @if (!$categories->isEmpty())
                                                <option {{ !$banner->category_id ? 'selected' : '' }} value="category">
                                                    {{ \App\CPU\translate('category') }}</option>
                                            @endif
                                            {{--                                            <option value="shop">{{ \App\CPU\translate('Shop')}}</option> --}}
                                            {{--                                            <option value="brand">{{ \App\CPU\translate('Brand')}}</option> --}}
                                        </select>
                                    </div>

                                    <div class="form-group d--none" id="home-positions">
                                        <label for="banner_type_home"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('banner_type') }}</label>
                                        <select id="banner_type_home"
                                            class="select2-no-search form-control w-100 banner_type" name="banner_type"
                                            required>
                                            <option value="Main Banner">{{ \App\CPU\translate('Main Banner') }}</option>
                                            <option value="Footer Banner">{{ \App\CPU\translate('Footer Banner') }}
                                            </option>
                                            <option value="Popup Banner">{{ \App\CPU\translate('Popup Banner') }}</option>
                                            {{-- <option value="Main Section Banner">
                                                {{ \App\CPU\translate('Main Section Banner') }}</option> --}}
                                        </select>
                                    </div>
                                    <div class="form-group d--none" id="category-positions">
                                        <label for="banner_type_category"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('banner_type') }}</label>
                                        <select id="banner_type_category"
                                            class="select2-no-search form-control w-100 banner_type" name="banner_type"
                                            required>
                                            <option value="Main Banner">{{ \App\CPU\translate('Main Banner') }}</option>
                                            <option value="Footer Banner">{{ \App\CPU\translate('Footer Banner') }}
                                            </option>
                                            <option value="Popup Banner">{{ \App\CPU\translate('Popup Banner') }}</option>
                                            {{-- <option value="Main Section Banner">
                                                {{ \App\CPU\translate('Main Section Banner') }}</option> --}}
                                        </select>
                                    </div>

                                    {{-- <div class="form-group mb-0" id="resource-product"
                                        style="display: {{ $banner['resource_type'] == 'product' ? 'block' : 'none' }}">
                                        <label for="product_id"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('product') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="product_id">
                                            @foreach (\App\Model\Product::active()->get() as $product)
                                                <option value="{{ $product['id'] }}"
                                                    {{ $banner['resource_id'] == $product['id'] ? 'selected' : '' }}>
                                                    {{ $product['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="form-group mb-0" id="resource-category"
                                        style="display: {{ $banner['resource_type'] == 'category' ? 'block' : 'none' }}">
                                        <label for="name"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('category') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="category_id">
                                            @foreach ($categories as $category)
                                                <option value="{{ $category['id'] }}"
                                                    {{ $banner['resource_id'] == $category['id'] ? 'selected' : '' }}>
                                                    {{ $category->translations[0]->value ?? $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- <div class="form-group mb-0" id="resource-shop"
                                        style="display: {{ $banner['resource_type'] == 'shop' ? 'block' : 'none' }}">
                                        <label for="shop_id"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('shop') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="shop_id">
                                            @foreach (\App\Model\Shop::active()->get() as $shop)
                                                <option value="{{ $shop['id'] }}"
                                                    {{ $banner['resource_id'] == $shop['id'] ? 'selected' : '' }}>
                                                    {{ $shop['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    {{-- <div class="form-group mb-0" id="resource-brand"
                                        style="display: {{ $banner['resource_type'] == 'brand' ? 'block' : 'none' }}">
                                        <label for="brand_id"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('brand') }}</label>
                                        <select class="js-example-responsive form-control w-100" name="brand_id">
                                            @foreach (\App\Model\Brand::all() as $brand)
                                                <option value="{{ $brand['id'] }}"
                                                    {{ $banner['resource_id'] == $brand['id'] ? 'selected' : '' }}>
                                                    {{ $brand['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div> --}}

                                    <div class="form-group d-flex gap-2">
                                        <div class="w-50">
                                            <label for="start_at"
                                                class="title-color text-capitalize">{{ \App\CPU\translate('start_at') }}</label>
                                            <input value="{{ $banner->start_at }}" type="datetime-local" name="start_at"
                                                class="form-control" id="start_at" required>
                                        </div>
                                        <div class="w-50">
                                            <label for="end_at"
                                                class="title-color text-capitalize">{{ \App\CPU\translate('end_at') }}</label>
                                            <input value="{{ $banner->end_at }}" type="datetime-local" name="end_at"
                                                class="form-control" id="end_at" required>
                                        </div>
                                    </div>
                                    <div class="form-group" id="banner-target">
                                        <label for="banner-target-input"
                                            class="title-color text-capitalize">{{ \App\CPU\translate('product with banner') }}</label>
                                        <select onchange="banner_target(this.value)" id="banner-target-input"
                                            class="select2-no-search form-control w-100" name="target_type">
                                            <option {{ $banner['target'] == 'all' ? 'selected' : '' }} value="all">
                                                {{ \App\CPU\translate('All Products') }}</option>
                                            <option {{ $banner['target'] == 'products' ? 'selected' : '' }}
                                                value="products">{{ \App\CPU\translate('chose product') }}</option>
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
                                        <center>
                                            <img class="ratio-4:1" id="mbImageviewer"
                                                src="{{ asset('storage/banner') }}/{{ $banner['photo'] }}"
                                                {{--                                                onerror='this.src="{{asset('assets/front-end/img/placeholder.png')}}"' --}} alt="" />
                                        </center>
                                        <label for="name"
                                            class="mt-3">{{ \App\CPU\translate('Image') }}</label><span
                                            class="ml-1 text-info">( {{ \App\CPU\translate('ratio') }} 4:1 )</span>
                                        <br>
                                        <div class="custom-file text-left">
                                            <input type="file" name="image" id="mbimageFileUploader"
                                                class="custom-file-input" accept="image/*">
                                            <label class="custom-file-label"
                                                for="mbimageFileUploader">{{ \App\CPU\translate('choose') }}
                                                {{ \App\CPU\translate('file') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end gap-3">
                                    <button type="reset"
                                        class="btn btn-secondary px-4">{{ \App\CPU\translate('reset') }}</button>
                                    <button type="submit"
                                        class="btn btn--primary px-4">{{ \App\CPU\translate('update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
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
            width: 'resolve'
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

        function banner_target(data) {
            if (data == 'products')
                $('#banner-target-products').show()
            else
                $('#banner-target-products').hide()

        }

        $('#resource_type').trigger('change')
        $('#banner-target-input').trigger('change')
        $('#banner-target-products-input').val({{ $target_products->toJSON() }}).trigger('change')

        function display_data(data) {

            // $('#resource-product').hide()
            // $('#resource-brand').hide()
            $('#resource-category').hide()
            // $('#resource-shop').hide()

            // if (data === 'product') {
            //     $('#resource-product').show()
            // } else 
            // if (data === 'brand') {
            //     $('#resource-brand').show()
            // } else 
            if (data === 'category') {
                $('#resource-category').show()
                $('#category-positions').show()
            }
            // else if (data === 'shop') {
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
    </script>
@endpush