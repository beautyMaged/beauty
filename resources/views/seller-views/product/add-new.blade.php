@extends('layouts.back-end.app-seller')

@section('title', \App\CPU\translate('product_add'))

@push('css_or_js')
    <link href="{{ asset('assets/back-end/css/tags-input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="http://localhost/6valley/assets/back-end/img/all-orders.png" class="mb-1 mr-1" alt="">
                {{ \App\CPU\translate('Add_new_product') }}
            </h2>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <form class="product-form" action="{{ route('seller.product.add-new') }}" method="post"
                    enctype="multipart/form-data"
                    style="text-align: {{ Session::get('direction') === 'rtl' ? 'right' : 'left' }};" id="product_form">
                    @csrf
                    <div class="card">
                        <div class="px-4 pt-3">
                            @php($language = \App\Model\BusinessSetting::where('type', 'pnc_language')->first())
                            @php($language = $language->value ?? null)
                            @php($default_lang = 'en')

                            @php($default_lang = json_decode($language)[0])
                            <ul class="nav nav-tabs w-fit-content mb-4">
                                @foreach (json_decode($language) as $lang)
                                    <li class="nav-item text-capitalize">
                                        <a class="nav-link lang_link {{ $lang == $default_lang ? 'active' : '' }}"
                                            href="#"
                                            id="{{ $lang }}-link">{{ \App\CPU\Helpers::get_language_name($lang) . '(' . strtoupper($lang) . ')' }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="card-body">
                            @foreach (json_decode($language) as $lang)
                                <div class="{{ $lang != $default_lang ? 'd-none' : '' }} lang_form"
                                    id="{{ $lang }}-form">
                                    <div class="form-group">
                                        <label class="title-color"
                                            for="{{ $lang }}_name">{{ \App\CPU\translate('name') }}
                                            ({{ strtoupper($lang) }})
                                        </label>
                                        <input type="text" {{ $lang == $default_lang ? 'required' : '' }} name="name[]"
                                            id="{{ $lang }}_name" class="form-control" placeholder="New Product"
                                            required>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{ $lang }}">
                                    <div class="form-group pt-4">
                                        <label class="title-color"
                                            for="{{ $lang }}_description">{{ \App\CPU\translate('description') }}
                                            ({{ strtoupper($lang) }})</label>
                                        <textarea name="description[]" class="editor textarea" cols="30" rows="10" required>{{ old('details') }}</textarea>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h5 class="mb-0">{{ \App\CPU\translate('General_info') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4 mb-3 ">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('product_type') }}</label>
                                        <select name="product_type" id="product_type" class="form-control" required>
                                            <option value="physical" selected>{{ \App\CPU\translate('physical') }}</option>
                                            @if($digital_product_setting)
                                                <option value="digital">{{ \App\CPU\translate('digital') }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 " id="digital_product_type_show">
                                        <label for="digital_product_type" class="title-color">{{ \App\CPU\translate("digital_product_type") }}</label>
                                        <select name="digital_product_type" id="digital_product_type" class="form-control" required>
                                            <option value="{{ old('category_id') }}" selected disabled>---Select---</option>
                                            <option value="ready_after_sell">{{ \App\CPU\translate("Ready After Sell") }}</option>
                                            <option value="ready_product">{{ \App\CPU\translate("Ready Product") }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 " id="digital_file_ready_show">
                                        <label for="digital_file_ready" class="title-color">{{ \App\CPU\translate("ready_product_upload") }}</label>
                                        <input type="file" name="digital_file_ready" id="digital_file_ready" class="form-control">
                                        <div class="mt-1 text-info">File type: jpg, jpeg, png, gif, zip, pdf</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3 ">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Category') }}</label>
                                        <select class="js-example-basic-multiple form-control" name="category_id"
                                            onchange="getRequest('{{ url('/') }}/seller/product/get-categories?parent_id='+this.value,'sub-category-select','select')"
                                            required>
                                            <option value="{{ old('category_id') }}" selected disabled>
                                                ---{{ \App\CPU\translate('Select') }}---</option>
                                            @foreach ($cat as $c)
                                                <option value="{{ $c['id'] }}"
                                                    {{ old('name') == $c['id'] ? 'selected' : '' }}>
                                                    {{ $c['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 ">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Sub_category') }}</label>
                                        <select class="js-example-basic-multiple form-control" name="sub_category_id"
                                            id="sub-category-select"
                                            onchange="getRequest('{{ url('/') }}/seller/product/get-categories?parent_id='+this.value,'sub-sub-category-select','select')">
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 ">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Sub_sub_category') }}</label>
                                        <select class="js-example-basic-multiple form-control" name="sub_sub_category_id"
                                            id="sub-sub-category-select">

                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3 ">
                                        <label for="code" class="title-color">{{ \App\CPU\translate('product_code_sku') }}
                                            <span class="text-danger">*</span>
                                            <a class="style-one-pro"
                                                onclick="document.getElementById('generate_number').value = getRndInteger()">{{ \App\CPU\translate('generate') }}
                                                {{ \App\CPU\translate('code') }}</a></label>
                                        <input type="number" minlength="5" id="generate_number" name="code"
                                            class="form-control" value="{{ old('code') }}"
                                            placeholder="{{ \App\CPU\translate('code') }}" required>
                                    </div>
                                    @if($brand_setting)
                                    <div class="col-md-4 mb-3 ">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Brand') }}</label>
                                        <select
                                            class="js-example-basic-multiple js-states js-example-responsive form-control"
                                            name="brand_id" required>
                                            <option value="{{ null }}" selected disabled>
                                                ---{{ \App\CPU\translate('Select') }}---</option>
                                            @foreach ($br as $b)
                                                <option value="{{ $b['id'] }}">{{ $b['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @endif

                                    <div class="col-md-4 mb-3 physical_product_show">
                                        <label for="name" class="title-color">{{ \App\CPU\translate('Unit') }}</label>
                                        <select class="js-example-basic-multiple form-control" name="unit">
                                            @foreach (\App\CPU\Helpers::units() as $x)
                                                <option value="{{ $x }}"
                                                    {{ old('unit') == $x ? 'selected' : '' }}>
                                                    {{ $x }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 rest-part physical_product_show">
                        <div class="card-header">
                            <h5 class="mb-0">{{ \App\CPU\translate('Variations') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row align-items-end">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex gap-2 mb-1">
                                            <label for="colors" class="title-color">
                                                {{ \App\CPU\translate('Colors') }} :
                                            </label>
                                            <label class="switcher">
                                                <input type="checkbox" class="switcher_input" id="color_switcher" value="{{ old('colors_active') }}"
                                                       name="colors_active">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                        <select class="js-example-basic-multiple js-states js-example-responsive form-control color-var-select"
                                            name="colors[]" multiple="multiple" id="colors-selector" disabled>
                                            @foreach (\App\Model\Color::orderBy('name', 'asc')->get() as $key => $color)
                                                <option value="{{ $color->code }}">
                                                    {{ $color['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="attributes" class="title-color">
                                            {{ \App\CPU\translate('Attributes') }} :
                                        </label>
                                        <select
                                            class="js-example-basic-multiple js-states js-example-responsive form-control"
                                            name="choice_attributes[]" id="choice_attributes" multiple="multiple">
                                            @foreach (\App\Model\Attribute::orderBy('name', 'asc')->get() as $key => $a)
                                                <option value="{{ $a['id'] }}">
                                                    {{ $a['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-12 mb-2">
                                        <div class="customer_choice_options" id="customer_choice_options">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-header">
                            <h5 class="mb-0">{{ \App\CPU\translate('Product_price_&_stock') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row align-items-end">
                                    <div class="col-md-4 mb-3">
                                        <label class="title-color">{{ \App\CPU\translate('Unit_price') }}</label>
                                        <input type="number" min="0" step="0.01"
                                            placeholder="{{ \App\CPU\translate('Unit_price') }}" name="unit_price"
                                            value="{{ old('unit_price') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="title-color">{{ \App\CPU\translate('Purchase_price') }}</label>
                                        <input type="number" min="0" step="0.01"
                                            placeholder="{{ \App\CPU\translate('Purchase_price') }}"
                                            name="purchase_price" value="{{ old('purchase_price') }}"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="title-color">{{ \App\CPU\translate('Tax') }}</label>
                                        <label class="badge badge-soft-info">{{ \App\CPU\translate('Percent') }} ( % )</label>
                                        <input type="number" min="0" value="0" step="0.01"
                                            placeholder="{{ \App\CPU\translate('Tax') }}" name="tax"
                                            value="{{ old('tax') }}" class="form-control">
                                        <input name="tax_type" value="percent" class="d--none">
                                    </div>
                                    <div class="col-md-4 form-group mb-3">
                                        <label class="title-color">{{ \App\CPU\translate('Tax_Model') }}</label>
                                        <select name="tax_model" class="form-control" required>
                                            <option value="include">{{ \App\CPU\translate("include") }}</option>
                                            <option value="exclude">{{ \App\CPU\translate("exclude") }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="title-color">{{ \App\CPU\translate('discount_type') }}</label>
                                        <select class="form-control js-select2-custom" name="discount_type">
                                            <option value="flat">{{ \App\CPU\translate('Flat') }}</option>
                                            <option value="percent">{{ \App\CPU\translate('Percent') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="title-color">{{ \App\CPU\translate('Discount') }}</label>
                                        <input type="number" min="0" value="0" step="0.01"
                                            placeholder="{{ \App\CPU\translate('Discount') }}" name="discount"
                                            value="{{ old('discount') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3 physical_product_show" id="quantity">
                                        <label class="title-color">{{ \App\CPU\translate('total') }}
                                            {{ \App\CPU\translate('Quantity') }}</label>
                                        <input type="number" min="0" value="0" step="1"
                                               placeholder="{{ \App\CPU\translate('Quantity') }}" name="current_stock"
                                               value="{{ old('current_stock') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3" id="minimum_order_qty">
                                        <label class="title-color">{{ \App\CPU\translate('minimum_order_quantity') }}</label>
                                        <input type="number" min="1" value="1" step="1"
                                            placeholder="{{ \App\CPU\translate('minimum_order_quantity') }}" name="minimum_order_qty"
                                               class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3 physical_product_show" id="shipping_cost">
                                        <label class="title-color">{{ \App\CPU\translate('shipping_cost') }} </label>
                                        <input type="number" min="0" value="0" step="1"
                                            placeholder="{{ \App\CPU\translate('shipping_cost') }}" name="shipping_cost"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-4 mb-3 physical_product_show" id="shipping_cost_multy">
                                        <div class="border rounded px-3 py-2 min-h-40 d-flex justify-content-between gap-3">
                                            <label
                                                class="title-color mb-0">{{ \App\CPU\translate('shipping_cost_multiply_with_quantity') }}
                                            </label>

                                            <label class="switcher">
                                                <input class="switcher_input" type="checkbox" name="multiplyQTY" id="">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="sku_combination mb-3" id="sku_combination"></div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 mb-2 rest-part">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span>{{ \App\CPU\translate('tags') }}</span>
                            </h5>
                        </div>
                        <div class="card-body pb-0">
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="title-color">{{ \App\CPU\translate('search_tags') }}</label>
                                        <input type="text" class="form-control" name="tags" data-role="tagsinput">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 mb-2 rest-part">
                        <div class="card-header">
                            <h5 class="mb-0">{{ \App\CPU\translate('seo_section') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label class="title-color">{{ \App\CPU\translate('Meta_title') }}</label>
                                    <input type="text" name="meta_title" placeholder="" class="form-control">
                                </div>

                                <div class="col-lg-4">
                                    <div class="form-group mb-0">
                                        <label class="title-color">{{ \App\CPU\translate('Meta_image') }}</label>
                                    </div>

                                    <div class="max-w-200">
                                        <div id="meta_img"></div>
                                    </div>
                                </div>

                                <div class="col-lg-8 mb-4">
                                    <label class="title-color">{{ \App\CPU\translate('Meta_description') }}</label>
                                    <textarea rows="10" type="text" name="meta_description" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-2 rest-part">
                        <div class="card-body __coba-aspect">
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <div class="d-flex flex-wrap gap-2 mb-2">
                                        <label class="title-color mb-0">{{ \App\CPU\translate('Youtube video link') }}</label>
                                        <span class="badge badge-soft-info"> (
                                            {{ \App\CPU\translate('optional, please provide embed link not direct link') }}.
                                            )</span>
                                    </div>

                                    <input type="text" name="video_link"
                                        placeholder="EX : https://www.youtube.com/embed/5R06LRdUCSE" class="form-control"
                                        required>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <div class="mb-2">
                                        <label class="title-color mb-0">{{ \App\CPU\translate('Upload_product_images') }}</label>
                                        <span class="text-info">* ( {{ \App\CPU\translate('ratio 1:1') }} )</span>
                                    </div>

                                    <div id="color_wise_image" class="row g-2 mb-4">
                                    </div>

                                    <div class="p-2 border border-dashed coba-area">
                                        <div class="row" id="coba"></div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-2">
                                        <label for="name" class="title-color mb-0">{{ \App\CPU\translate('Upload_thumbnail') }}</label>
                                        <span class="text-info">* ( {{ \App\CPU\translate('ratio 1:1') }} )</span>
                                    </div>
                                    <div class="row g-2" id="thumbnail"></div>
                                </div>
                                <div class="col-12">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" onclick="check()"
                                            class="btn btn--primary">{{ \App\CPU\translate('Submit') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/back-end') }}/js/tags-input.min.js"></script>
    <script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/back-end/js/spartan-multi-image-picker.js') }}"></script>
    <script>
        $(function() {
            $('#color_switcher').click(function(){
                var checkBoxes = $("#color_switcher");
                if ($('#color_switcher').prop('checked')) {
                    $('#color_wise_image').show();
                } else {
                    $('#color_wise_image').hide();
                }
            });

            $("#coba").spartanMultiImagePicker({
                fieldName: 'images[]',
                maxCount: 10,
                // rowHeight: '220px',
                groupClassName: 'col-6 col-lg-4 col-xl-3',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('assets/back-end/img/400x400/img2.jpg') }}',
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        '{{ \App\CPU\translate('Please only input png or jpg type file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ \App\CPU\translate('File size too big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

            $("#thumbnail").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: 'auto',
                groupClassName: 'col-6 col-md-12 col-lg-8 col-xl-6',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('assets/back-end/img/400x400/img2.jpg') }}',
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        '{{ \App\CPU\translate('Please only input png or jpg type file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ \App\CPU\translate('File size too big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });

            $("#meta_img").spartanMultiImagePicker({
                fieldName: 'meta_image',
                maxCount: 1,
                // rowHeight: '220px',
                groupClassName: '',
                maxFileSize: '',
                placeholderImage: {
                    image: '{{ asset('assets/back-end/img/400x400/img2.jpg') }}',
                    width: '100%',
                },
                dropFileLabel: "Drop Here",
                onAddRow: function(index, file) {

                },
                onRenderedPreview: function(index) {

                },
                onRemoveRow: function(index) {

                },
                onExtensionErr: function(index, file) {
                    toastr.error(
                        '{{ \App\CPU\translate('Please only input png or jpg type file') }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                },
                onSizeErr: function(index, file) {
                    toastr.error('{{ \App\CPU\translate('File size too big') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        function getRequest(route, id, type) {
            $.get({
                url: route,
                dataType: 'json',
                success: function(data) {
                    if (type == 'select') {
                        $('#' + id).empty().append(data.select_tag);
                    }
                },
            });
        }

        $('input[name="colors_active"]').on('change', function() {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });

        $('#choice_attributes').on('change', function() {
            $('#customer_choice_options').html(null);
            $.each($("#choice_attributes option:selected"), function() {
                //console.log($(this).val());
                add_more_customer_choice_option($(this).val(), $(this).text());
            });
        });

        function add_more_customer_choice_option(i, name) {
            let n = name.split(' ').join('');
            $('#customer_choice_options').append(
                '<div class="row"><div class="col-md-3"><input type="hidden" name="choice_no[]" value="' + i +
                '"><input type="text" class="form-control" name="choice[]" value="' + n +
                '" placeholder="{{ trans('Choice Title') }}" readonly></div><div class="col-lg-9"><input type="text" class="form-control" name="choice_options_' +
                i +
                '[]" placeholder="{{ trans('Enter choice values') }}" data-role="tagsinput" onchange="update_sku()"></div></div>'
            );

            $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
        }
        $('#colors-selector').on('change', function() {
            update_sku();
            $('#color_switcher').prop('checked')
            {
                color_wise_image($('#colors-selector'));
            }
        });

        $('input[name="unit_price"]').on('keyup', function() {
            let product_type = $('#product_type').val();
            if(product_type === 'physical') {
                update_sku();
            }
        });

        function color_wise_image(t){
            let colors = t.val();
            $('#color_wise_image').html('')
            $.each(colors, function(key, value){
                let value_id = value.replace('#','');
                let color= "color_image_"+value_id;

                let html = ` <div class='col-6 col-lg-4 col-xl-3'> <label style='border: 2px dashed #ddd; border-radius: 3px; cursor: pointer; text-align: center; overflow: hidden; padding: 5px; margin-top: 5px; margin-bottom : 5px; position : relative; display: flex; align-items: center; margin: auto; justify-content: center; flex-direction: column;'>
                                <span class="upload--icon" style="background: ${value}">
                                <i class="tio-edit"></i>
                                    <input type="file" name="`+color+`" id="`+value_id+`" class="d-none" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" required="">
                                </span>
                                <img src="{{ asset('assets/back-end/img/400x400/img2.jpg') }}" style="object-fit: cover;aspect-ratio:1"  alt="public/img">
                              </label> </div>`;
                $('#color_wise_image').append(html)

                $("#color_wise_image input[type='file']").each(function () {

                    var $this = $(this).closest('label');

                    function proPicURL(input) {
                        if (input.files && input.files[0]) {
                            var uploadedFile = new FileReader();
                            uploadedFile.onload = function (e) {
                                $this.find('img').attr('src', e.target.result);
                                $this.fadeIn(300);
                            };
                            uploadedFile.readAsDataURL(input.files[0]);
                        }
                    }
                    $(this)
                        .on("change", function () {
                            proPicURL(this);
                        });
                });
            });
        }

        function update_sku() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: '{{ route('seller.product.sku-combination') }}',
                data: $('#product_form').serialize(),
                success: function(data) {
                    $('#sku_combination').html(data.view);
                    $('#sku_combination').addClass('pt-4');
                    if (data.length > 1) {
                        $('#quantity').hide();
                    } else {
                        $('#quantity').show();
                    }
                }
            });
        };

        $(document).ready(function() {
            // color select select2
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function(m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state
                    .text;
            }
        });
    </script>

    <script>
        function check() {
            Swal.fire({
                title: '{{ \App\CPU\translate('Are you sure') }}?',
                text: '',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                var formData = new FormData(document.getElementById('product_form'));
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{ route('seller.product.add-new') }}',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            toastr.success(
                                '{{ \App\CPU\translate('product updated successfully!') }}', {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            $('#product_form').submit();
                        }
                    }
                });
            })
        };
    </script>

    <script>
        $(".lang_link").click(function(e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{ $default_lang }}') {
                $(".rest-part").removeClass('d-none');
            } else {
                $(".rest-part").addClass('d-none');
            }
        })

        $(document).ready(function(){
            product_type();
            digital_product_type();

            $('#product_type').change(function(){
                product_type();
            });

            $('#digital_product_type').change(function(){
                digital_product_type();
            });
        });

        function product_type(){
            let product_type = $('#product_type').val();

            if(product_type === 'physical'){
                $('#digital_product_type_show').hide();
                $('#digital_file_ready_show').hide();
                $('.physical_product_show').show();
                $('#digital_product_type').val($('#digital_product_type option:first').val());
                $('#digital_file_ready').val('');
            }else if(product_type === 'digital'){
                $('#digital_product_type_show').show();
                $('.physical_product_show').hide();

            }
        }

        function digital_product_type(){
            let digital_product_type = $('#digital_product_type').val();
            if (digital_product_type === 'ready_product') {
                $('#digital_file_ready_show').show();
            } else if (digital_product_type === 'ready_after_sell') {
                $('#digital_file_ready_show').hide();
                $("#digital_file_ready").val('');
            }
        }
    </script>

    {{-- ck editor --}}
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/ckeditor.js"></script>
    <script src="{{ asset('/') }}vendor/ckeditor/ckeditor/adapters/jquery.js"></script>
    <script>
        $('.textarea').ckeditor({
            contentsLangDirection: '{{ Session::get('direction') }}',
        });
    </script>
    {{-- ck editor --}}
@endpush
