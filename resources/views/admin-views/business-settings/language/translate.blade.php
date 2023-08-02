@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Language Translate'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Heading -->
        <nav aria-label="breadcrumb" class="w-100"
             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Language')}}</li>
            </ol>
        </nav>

        <div class="row __mt-20">
            <div class="col-md-12">
                <div class="card" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <div class="card-header">
                        <h5>{{\App\CPU\translate('language_content_table')}}</h5>
                        <a href="{{route('admin.business-settings.language.index')}}"
                           class="btn btn-sm btn-danger btn-icon-split float-right">
                            <span class="text text-capitalize">{{\App\CPU\translate('back')}}</span>
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>
                                    <th>{{\App\CPU\translate('SL#')}}</th>
                                    <th style="width: 400px">{{\App\CPU\translate('key')}}</th>
                                    <th style="min-width: 300px">{{\App\CPU\translate('value')}}</th>
                                    <th>{{\App\CPU\translate('auto_translate')}}</th>
                                    <th>{{\App\CPU\translate('update')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($count=0)
                                @foreach($full_data as $key=>$value)
                                    @php($count++)
                                    <tr id="lang-{{$count}}">
                                        <td>{{$count}}</td>
                                        <td>
                                            @php($key=\App\CPU\Helpers::remove_invalid_charcaters($key))
                                            <input type="text" name="key[]"
                                                   value="{{$key}}" hidden>
                                            <label>{{$key}}</label>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="value[]"
                                                   id="value-{{$count}}"
                                                   value="{{$value}}">
                                        </td>
                                        <td class="__w-100px">
                                            <button type="button"
                                                    onclick="auto_translate('{{$key}}',{{$count}})"
                                                    class="btn btn-ghost-success btn-block"><i class="tio-globe"></i>
                                            </button>
                                        </td>
                                        <td class="__w-100px">
                                            <button type="button"
                                                    onclick="update_lang('{{$key}}',$('#value-{{$count}}').val())"
                                                    class="btn btn--primary btn-block"><i class="tio-save-outlined"></i>
                                            </button>
                                        </td>
{{--                                        <td class="__w-100px">--}}
{{--                                            <button type="button"--}}
{{--                                                    onclick="remove_key('{{$key}}',{{$count}})"--}}
{{--                                                    class="btn btn-danger btn-block"><i class="tio-add-to-trash"></i>--}}
{{--                                            </button>--}}
{{--                                        </td>--}}
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable({
                "pageLength": {{\App\CPU\Helpers::pagination_limit()}}
            });
        });

        function update_lang(key, value) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.language.translate-submit',[$lang])}}",
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{\App\CPU\translate('text_updated_successfully')}}');
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function remove_key(key, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.language.remove-key',[$lang])}}",
                method: 'POST',
                data: {
                    key: key
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{\App\CPU\translate('Key removed successfully')}}');
                    $('#lang-' + id).hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function auto_translate(key, id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.business-settings.language.auto-translate',[$lang])}}",
                method: 'POST',
                data: {
                    key: key
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    toastr.success('{{\App\CPU\translate('Key translated successfully')}}');
                    console.log(response.translated_data)
                    $('#value-'+id).val(response.translated_data);
                    //$('#value-' + id).text(response.translated_data);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>

@endpush
