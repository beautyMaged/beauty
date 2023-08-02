@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('announcement'))

@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Title -->
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/public/assets/back-end/img/announcement.png')}}" alt="">
                {{\App\CPU\translate('Announcement_Setup')}}
            </h2>
        </div>
        <!-- End Page Title -->

        <form action="{{ route('admin.business-settings.update-announcement') }}" method="POST"
                enctype="multipart/form-data">
            @csrf
            @if (isset($announcement))
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">{{\App\CPU\translate('announcement_setup')}}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-10 align-items-center mb-2">
                            <input type="radio" name="announcement_status"
                                    value="1" {{$announcement['status']==1?'checked':''}}>
                            <label class="title-color mb-0">{{\App\CPU\translate('Active')}}</label>
                        </div>
                        <div class="d-flex gap-10 align-items-center mb-4">
                            <input type="radio" name="announcement_status"
                                    value="0" {{$announcement['status']==0?'checked':''}}>
                            <label class="title-color mb-0">{{\App\CPU\translate('Inactive')}}</label>
                        </div>
                        <div class="d-flex flex-wrap gap-4">
                            <div class="form-group text-center">
                                <label class="title-color">{{\App\CPU\translate('background_color')}}</label>
                                <input type="color" name="announcement_color"
                                        value="{{ $announcement['color'] }}" id="background-color"
                                        class="form-control form-control_color">
                                <div class="title-color mb-4 mt-3" id="background-color-set">{{ $announcement['color'] }}</div>
                            </div>
                            <div class="form-group text-center">
                                <label class="title-color">{{\App\CPU\translate('text_color')}}</label>
                                <input type="color" name="text_color" id="text-color" value="{{ $announcement['text_color'] }}"
                                        class="form-control form-control_color">
                                <div class="title-color mb-4 mt-3" id="text-color-set">{{ $announcement['text_color'] }}</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="title-color d-flex">{{\App\CPU\translate('text')}}</label>
                            <input class="form-control" type="text" name="announcement"
                                    value="{{ $announcement['announcement'] }}">
                        </div>

                        <div class="justify-content-end d-flex">
                            <button type="submit" class="btn btn--primary px-4">{{\App\CPU\translate('publish')}}</button>
                        </div>
                    </div>
                </div>
            @endif
        </form>
    </div>
@endsection

@push('script_2')
    <script>
        $('#background-color').on('change', function(){
            let background_color = $('#background-color').val();
            $('#background-color-set').text(background_color);
        });
        $('#text-color').on('change', function(){
            let text_color = $('#text-color').val();
            $('#text-color-set').text(text_color);
        });
    </script>
@endpush
