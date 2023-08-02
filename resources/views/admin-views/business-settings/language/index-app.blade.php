@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Language'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid __inline-3">
        <!-- Page Heading -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('language_setting')}} {{\App\CPU\translate('for_app')}}</li>
            </ol>
        </nav>

        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="alert alert-warning sticky-top" id="alert_box" style="display:none;">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong>{{\App\CPU\translate('Warning')}}!</strong> {{\App\CPU\translate('Follow the documentation to setup from app end')}}, <a
                        href="https://documentation.6amtech.com/sixvalley-ecommerce/docs/1.0/app-setup#section3"
                        target="_blank">{{\App\CPU\translate('click')}}
                        {{\App\CPU\translate('here')}}</a>.
                </div>
                <div class="card">
                    <div class="card-header">
                        <h4>{{\App\CPU\translate('Select Country code')}}
                            {{\App\CPU\translate('for')}} {{\App\CPU\translate('product')}} {{\App\CPU\translate('and')}} {{\App\CPU\translate('category')}} {{\App\CPU\translate('language')}}</h4>
                        <label class="badge badge-danger">* {{\App\CPU\translate('For mobile app only')}}</label>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.business-settings.web-config.update-language')}}" method="post">
                            @csrf
                            @php($language=\App\Model\BusinessSetting::where('type','pnc_language')->first())
                            @php($language = json_decode($language->value,true) ?? [])

                            <div class="form-group">
                                <select name="language[]" id="language" onchange="$('#alert_box').show();"
                                        data-maximum-selection-length="3" class="form-control js-select2-custom country-var-select"
                                        required multiple=true>
                                    @foreach(\Illuminate\Support\Facades\File::files(base_path('public/assets/front-end/img/flags')) as $path)
                                        <option value="{{ pathinfo($path)['filename'] }}"
                                                {{in_array(pathinfo($path)['filename'],$language)?'selected':''}}
                                                title="{{ asset('public/assets/front-end/img/flags/'.pathinfo($path)['filename'].'.png') }}">
                                            {{ strtoupper(pathinfo($path)['filename']) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit"
                                    class="btn btn--primary float-right ml-3">{{\App\CPU\translate('Save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            // color select select2
            $('.country-var-select').select2({
                templateResult: codeSelect,
                templateSelection: codeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function codeSelect(state) {
                var code = state.title;
                if (!code) return state.text;
                return "<img class='image-preview' src='"+code+"'>" + state.text;
            }
        });
    </script>
@endpush
