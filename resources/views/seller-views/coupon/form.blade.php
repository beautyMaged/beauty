@extends('layouts.back-end.app-seller')
@if ($view == 'create')
    @section('title', \App\CPU\translate('Coupon Add'))
@else
    @section('title', \App\CPU\translate('Coupon Edit'))
@endif
@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css') }}" rel="stylesheet">
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
        <form
            @if ($view == 'create') action="{{ route('seller.coupon.store-coupon') }}"
         @else
         action="{{ route('seller.coupon.update', ['id' => $coupon->id]) }}" @endif
            method="post">
            @csrf
            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    {{ \App\CPU\translate('coupon data') }}
                </h2>
            </div>
            <!-- Content Row -->
            <div class="row mb-3">
                <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <div class="d-flex justify-content-between">
                                        <label for="name"
                                            class="title-color font-weight-medium text-capitalize">{{ \App\CPU\translate('coupon_code') }}</label>
                                        <a href="javascript:void(0)" class="float-right c1 fz-12"
                                            onclick="generateCode()">{{ \App\CPU\translate('generate_code') }}</a>
                                    </div>
                                    <input type="text" name="code" value="{{ $coupon?->code }}" class="form-control"
                                        id="code" placeholder="{{ \App\CPU\translate('Ex: EID100') }}" required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="name"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('discount_type') }}</label>
                                    <select id="discount_type" class="select2-no-search form-control w-100"
                                        name="discount_type">
                                        <option {{ $coupon?->discount_type == 'amount' ? 'selected' : '' }} value="amount">
                                            {{ \App\CPU\translate('Amount') }}</option>
                                        <option {{ $coupon?->discount_type == 'percentage' ? 'selected' : '' }}
                                            value="percentage">{{ \App\CPU\translate('percentage (%)') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="discount"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('Discount_Amount') }}
                                        <span id="discount_percent"> (%)</span>
                                    </label>
                                    <input type="number" step="any" min="1" max="1000000" name="discount"
                                        value="{{ $coupon?->discount }}" value="{{ old('discount') }}"
                                        class="form-control" id="discount"
                                        placeholder="{{ \App\CPU\translate('Ex: 500') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group" id="max-discount">
                                    <label for="name"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('maximum_discount ($)') }}</label>
                                    <input type="number" step="any" min="1" max="1000000" name="max_discount"
                                        value="{{ $coupon?->max_discount }}" value="{{ old('max_discount') }}"
                                        class="form-control" id="maximum discount"
                                        placeholder="{{ \App\CPU\translate('Ex: 5000') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="free_delivery"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('Free_Delivery') }}</label>
                                    <select class="form-control select2-no-search" id="free_delivery" name="free_delivery"
                                        required>
                                        <option {{ $coupon?->free_delivery == 'true' ? 'selected' : '' }} value="true">
                                            {{ \App\CPU\translate('Yes') }}</option>
                                        <option {{ $coupon?->free_delivery == 'false' ? 'selected' : '' }} value="false">
                                            {{ \App\CPU\translate('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="exclude_discounted"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('Exclude discounted products') }}</label>
                                    <select class="form-control select2-no-search" id="exclude_discounted"
                                        name="exclude_discounted" required>
                                        <option {{ $coupon?->exclude_discounted == 'true' ? 'selected' : '' }}
                                            value="true">{{ \App\CPU\translate('Yes') }}</option>
                                        <option {{ $coupon?->exclude_discounted == 'false' ? 'selected' : '' }}
                                            value="false">{{ \App\CPU\translate('No') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="limit_once" class="title-color font-weight-medium d-flex">
                                        {{ \App\CPU\translate('limit for one user') }}
                                    </label>
                                    <input id="limit_once" type="number" step="any" name="limit_once"
                                        value="{{ $coupon?->limit_once }}" value="{{ old('limit_once') }}" min="0"
                                        class="form-control"
                                        placeholder="{{ \App\CPU\translate('EX') }}: {{ \App\CPU\translate('10') }}">
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="limit_all" class="title-color font-weight-medium d-flex">

                                        {{ \App\CPU\translate('limit for all users') }}</label>
                                    <input id="limit_all" type="number" step="any" name="limit_all"
                                        value="{{ $coupon?->limit_all }}" value="{{ old('limit_all') }}" min="0"
                                        class="form-control"
                                        placeholder="{{ \App\CPU\translate('EX') }}: {{ \App\CPU\translate('10') }}">
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="minimum_purchase"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('minimum_purchase') }}</label>
                                    <input type="number" step="any" min="1" max="1000000"
                                        name="min_purchase" value="{{ $coupon?->min_purchase }}"
                                        value="{{ old('min_purchase') }}" class="form-control" id="minimum_purchase"
                                        placeholder="{{ \App\CPU\translate('Ex: 100') }}">
                                </div>

                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="start_at"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('start_date') }}</label>
                                    <input id="start_at" type="datetime-local" name="start_at"
                                        value="{{ $coupon?->start_at }}" value="{{ old('start_at') }}"
                                        class="form-control" placeholder="{{ \App\CPU\translate('start date') }}"
                                        required>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="end_at"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('expire_date') }}</label>
                                    <input id="end_at" type="datetime-local" name="end_at"
                                        value="{{ $coupon?->end_at }}" value="{{ old('end_at') }}" class="form-control"
                                        placeholder="{{ \App\CPU\translate('expire date') }}" required>
                                </div>
                            </div>

                            {{-- <div class="d-flex align-items-center justify-content-end flex-wrap gap-10">
                                <button type="reset"
                                    class="btn btn-secondary px-4">{{ \App\CPU\translate('reset') }}</button>
                                <button type="submit"
                                    class="btn btn--primary px-4">{{ \App\CPU\translate('Submit') }}</button>
                            </div> --}}

                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    {{ \App\CPU\translate('included in the coupon') }}
                </h2>
            </div>
            <!-- Content Row -->
            <div class="row mb-3">
                <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    @php
                                        $payment_methods = ['credit_card', 'paypal', 'mada', 'bank_transfer', 'apple_pay'];
                                    @endphp
                                    <label for="payment_methods"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('Payment methods included in the coupon') }}</label>
                                    <select class="form-control select2-multiple select2-hidden-accessible"
                                        id="payment_methods" name="payment_methods[]" required multiple>
                                        <option value="cash_on_delivery" disabled>
                                            {{ \App\CPU\translate('cash_on_delivery') }}</option>
                                        {{-- <option value="all">{{ \App\CPU\translate('All payment methods') }}</option> --}}
                                        @foreach ($payment_methods as $payment_method)
                                            <option
                                                {{ $coupon && in_array($payment_method, $coupon->payment_methods) ? 'selected' : '' }}
                                                value="{{ $payment_method }}">
                                                {{ \App\CPU\translate($payment_method) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="categories"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('included categories') }}</label>
                                    <select class="form-control select2-multiple select2-hidden-accessible" multiple
                                        id="categories" name="categories[]" value="{{ $coupon?->categories }}">
                                        @foreach ($seller->categories() as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $coupon && $coupon->categories->contains([$category->id, 'included']) ? 'selected' : '' }}>
                                                {{ $category->translations[0]->value ?? $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="products"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('included products') }}</label>
                                    <select class="form-control select2-multiple select2-hidden-accessible" multiple
                                        id="products" name="products[]">
                                        @foreach ($seller->products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $coupon && $coupon->products->contains([$product->id, 'included']) ? 'selected' : '' }}>
                                                {{ $product->translations[0]->value ?? $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Title -->
            <div class="mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    {{ \App\CPU\translate('excluded from the coupon') }}
                </h2>
            </div>
            <!-- Content Row -->
            <div class="row mb-3">
                <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="excluded_brands"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('excluded brands') }}</label>
                                    <select class="form-control select2-multiple select2-hidden-accessible" multiple
                                        id="excluded_brands" name="excluded_brands[]">
                                        @foreach ($seller->brands() as $brand)
                                            <option value="{{ $brand->id }}"
                                                {{ $coupon && $coupon->brands->contains([$brand->id, 'excluded']) ? 'selected' : '' }}>
                                                {{ $brand->translations[0]->value ?? $brand->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="excluded_categories"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('excluded categories') }}</label>
                                    <select class="form-control select2-multiple select2-hidden-accessible" multiple
                                        id="excluded_categories" name="excluded_categories[]">
                                        @foreach ($seller->categories() as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $coupon && $coupon->categories->contains([$category->id, 'excluded']) ? 'selected' : '' }}>
                                                {{ $category->translations[0]->value ?? $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 form-group">
                                    <label for="excluded_products"
                                        class="title-color font-weight-medium d-flex">{{ \App\CPU\translate('excluded products') }}</label>
                                    <select class="form-control select2-multiple select2-hidden-accessible" multiple
                                        id="excluded_products" name="excluded_products[]">
                                        @foreach ($seller->products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $coupon && $coupon->products->contains([$product->id, 'excluded']) ? 'selected' : '' }}>
                                                {{ $product->translations[0]->value ?? $product->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="d-flex align-items-center flex-wrap gap-10">
                                        <button type="reset"
                                            class="btn btn-secondary px-4">{{ \App\CPU\translate('reset') }}</button>
                                        <button type="submit"
                                            class="btn btn--primary px-4">{{ \App\CPU\translate('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {

            $('#start_date').attr('min', (new Date()).toISOString().split('T')[0]);
            $('#expire_date').attr('min', (new Date()).toISOString().split('T')[0]);

            field_show_hide();
        });

        $("#start_date").on("change", function() {
            $('#expire_date').attr('min', $(this).val());
        });

        $("#expire_date").on("change", function() {
            $('#start_date').attr('max', $(this).val());
        });

        function generateCode() {
            let code = Math.random().toString(36).substring(2, 12);
            $('#code').val(code)
        }

        $('#discount_type').on('change', function() {
            let discount_type = $('#discount_type').val();
            if (discount_type === 'percentage') {
                $('#discount').attr("max", "100");
                $('#discount').attr('placeholder', 'Ex: 10%');
                $('#discount_percent').hide()
                $('#max-discount').show()

            }
            if (discount_type === 'amount') {
                $('#discount').attr("max", "1000000");
                $('#discount').attr('placeholder', 'Ex: 500');
                $('#discount_percent').show()
                $('#max-discount').hide()
            }
        })

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
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
        });

        $('#coupon_type').on('change', function() {
            field_show_hide();
        });

        [
            'excluded_products',
            'free_delivery',
            'exclude_discounted',
            'payment_methods',
            'categories',
            'products',
            'excluded_categories',
            'excluded_brands',
        ].forEach(selector => {
            if (!$(`#${selector} option[selected]`).length)
                $(`#${selector}`).val(null).trigger('change')
        })

        var switchSelect = selectors => $(selectors[0]).on('change', e => {
            let resource = $(selectors[0])
            let excluded = $(selectors[1])
            let excludedValues = excluded.val()
            let resourceValues = resource.val()
            resourceValues.forEach(resourceValue => {
                if (excludedValues.includes(resourceValue))
                    excluded.val(excludedValues.filter(v => v != resourceValue)).trigger('change')
            })
        });
        [
            ['#categories', '#excluded_categories'],
            ['#products', '#excluded_products'],
        ].forEach(selectors => {
            switchSelect.call(null, selectors)
            switchSelect.call(null, selectors.slice().reverse())
        })

        function field_show_hide() {
            let discount_type = $('#discount_type').val();
            let type = $('#coupon_type').val();

            if (type === 'free_delivery') {
                if (discount_type === 'amount') {
                    $('.free_delivery').hide();
                } else if (discount_type === 'percentage') {
                    $('.free_delivery').hide();
                }
            } else {
                if (discount_type === 'amount') {
                    $('.free_delivery').show();
                } else if (discount_type === 'percentage') {
                    $('.free_delivery').show();
                }
            }
        }
    </script>
@endpush
