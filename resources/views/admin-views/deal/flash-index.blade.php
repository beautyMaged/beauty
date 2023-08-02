@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Flash Deal'))

@push('css_or_js')
    <link href="{{asset('assets/back-end/css/tags-input.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <!-- Page Title -->
    <div class="mb-3">
        <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
            <img width="20" src="{{asset('/assets/back-end/img/flash_deal.png')}}" alt="">
            {{\App\CPU\translate('flash_deals')}}
        </h2>
    </div>
    <!-- End Page Title -->

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{route('admin.deal.flash')}}" method="post" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" enctype="multipart/form-data">
                        @csrf
                        @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                        @php($language = $language->value ?? null)
                        @php($default_lang = 'en')

                        @php($default_lang = json_decode($language)[0])
                        <ul class="nav nav-tabs w-fit-content mb-4">
                            @foreach(json_decode($language) as $lang)
                                <li class="nav-item text-capitalize font-weight-medium">
                                    <a class="nav-link lang_link {{$lang == $default_lang? 'active':''}}"
                                       href="#"
                                       id="{{$lang}}-link">{{\App\CPU\Helpers::get_language_name($lang).'('.strtoupper($lang).')'}}</a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="row">
                            <div class="col-lg-6">
                                @foreach(json_decode($language) as $lang)
                                    <div class="{{$lang != $default_lang ? 'd-none':''}} lang_form" id="{{$lang}}-form">
                                        <input type="text" name="deal_type" value="flash_deal"  class="d-none">
                                        <div class="form-group">
                                            <label for="name" class="title-color font-weight-medium text-capitalize">{{ \App\CPU\translate('Title')}} ({{strtoupper($lang)}})</label>
                                            <input type="text" name="title[]" class="form-control" id="title"
                                                placeholder="{{\App\CPU\translate('Ex')}} : {{\App\CPU\translate('LUX')}}"
                                                {{$lang == $default_lang? 'required':''}}>
                                        </div>
                                    </div>
                                    <input type="hidden" name="lang[]" value="{{$lang}}" id="lang">
                                @endforeach
                                <div class="form-group">
                                    <label for="name" class="title-color font-weight-medium text-capitalize">{{ \App\CPU\translate('start_date')}}</label>
                                    <input type="date" name="start_date" required class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="name" class="title-color font-weight-medium text-capitalize">{{ \App\CPU\translate('end_date')}}</label>
                                    <input type="date" name="end_date" required class="form-control">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <div class="text-center">
                                        <img class="border radius-10 ratio-4:1 max-w-655px w-100" id="viewer"
                                            src="{{asset('assets/front-end/img/placeholder.png')}}" alt="banner image"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="name" class="title-color font-weight-medium text-capitalize">{{\App\CPU\translate('Upload_image')}}</label>
                                    <span class="text-info ml-1">( {{\App\CPU\translate('ratio')}} 5:1 )</span>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label text-capitalize" for="customFileUpload">{{\App\CPU\translate('choose_file')}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-3">
                            <button type="reset" id="reset" class="btn btn-secondary px-4">{{ \App\CPU\translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary px-4">{{ \App\CPU\translate('submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-20">
        <div class="col-md-12">
            <div class="card">
                <div class="px-3 py-4">
                    <div class="row align-items-center">
                        <div class="col-sm-4 col-md-6 col-lg-8 mb-2 mb-sm-0">
                            <h5 class="mb-0 text-capitalize d-flex gap-2">
                                {{ \App\CPU\translate('flash_deal_table')}}
                                <span class="badge badge-soft-dark radius-50 fz-12">{{ $flash_deal->total() }}</span>
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
                                        placeholder="{{\App\CPU\translate('Search by Title')}}" aria-label="Search orders" value="{{ $search }}" required>
                                    <button type="submit" class="btn btn--primary">{{\App\CPU\translate('search')}}</button>
                                </div>
                            </form>
                            <!-- End Search -->
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable"
                            style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                            class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ \App\CPU\translate('SL')}}</th>
                            <th>{{ \App\CPU\translate('Title')}}</th>
                            <th>{{ \App\CPU\translate('Duration')}}</th>
                            <th>{{ \App\CPU\translate('status')}}</th>
                            <th>{{ \App\CPU\translate('active_products')}}</th>
                            <th>{{ \App\CPU\translate('publish')}}</th>
                            <th class="text-center">{{ \App\CPU\translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($flash_deal as $k=>$deal)
                            <tr>
                                <td>{{$flash_deal->firstItem()+ $k}}</td>
                                <td><span class="font-weight-semibold">{{$deal['title']}}</span></td>
                                <td>{{date('d-M-y',strtotime($deal['start_date']))}} - {{date('d-M-y',strtotime($deal['end_date']))}}</td>
                                <!-- <td>{{date('d-M-y',strtotime($deal['end_date']))}}</td> -->
                                <td>
                                    @if(\Carbon\Carbon::parse($deal['end_date'])->endOfDay()->isPast())
                                        <span class="badge badge-soft-danger">{{ \App\CPU\translate('expired')}} </span>
                                    @else
                                        <span class="badge badge-soft-success"> {{ \App\CPU\translate('active')}} </span>
                                    @endif
                                </td>
                                <td>{{ $deal->products_count }}</td>
                                <td>
                                    <label class="switcher">
                                        <input type="checkbox" class="switcher_input status check_uncheck"
                                                id="{{$deal['id']}}" {{$deal->status == 1?'checked':''}}>
                                        <span class="switcher_control"></span>
                                    </label>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-10">
                                        <a class="h-30 d-flex gap-2 align-items-center btn btn-soft-info btn-sm border-info" href="{{route('admin.deal.add-product',[$deal['id']])}}">
                                            <img src="{{asset('/assets/back-end/img/plus.svg')}}" class="svg" alt="">
                                            {{\App\CPU\translate('Add Product')}}
                                        </a>

                                        <a title="{{\App\CPU\translate('Edit')}}"
                                            href="{{route('admin.deal.update',[$deal['id']])}}"
                                            class="btn btn-outline--primary btn-sm edit">
                                            <i class="tio-edit"></i>
                                        </a>
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
                        {{$flash_deal->links()}}
                    </div>
                </div>

                @if(count($flash_deal)==0)
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
    <!-- Page level plugins -->
    <script src="{{asset('assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->

    <script src="{{asset('assets/back-end')}}/js/select2.min.js"></script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.deal.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    setTimeout(function (){
                        location.reload()
                    },1000);
                }
            });
        });

    </script>

    <!-- Page level custom scripts -->

    <script>
        $(document).ready(function () {
            // color select select2
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>

    <script>
        $(".lang_link").click(function (e) {
            e.preventDefault();
            $(".lang_link").removeClass('active');
            $(".lang_form").addClass('d-none');
            $(this).addClass('active');

            let form_id = this.id;
            let lang = form_id.split("-")[0];
            console.log(lang);
            $("#" + lang + "-form").removeClass('d-none');
            if (lang == '{{$default_lang}}') {
                $(".from_part_2").removeClass('d-none');
            } else {
                $(".from_part_2").addClass('d-none');
            }
        });

        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
