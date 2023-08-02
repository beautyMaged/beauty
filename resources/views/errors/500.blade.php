@extends('errors::minimal')

{{--@section('title', __('Not Found'))
@section('code', '404')--}}

@section('message')
    <style>
        .for-margin {
            margin: auto;

            margin-bottom: 10%;
        }

        .for-margin {

        }

        .page-not-found {
            margin-top: 30px;
            font-weight: 600;
            text-align: center;
        }
    </style>
    <div class="container ">
        <div class="col-md-3"></div>
        <div class="col-md-6 for-margin">
            <div class="for-image">
                <img style="" src="{{asset("storage/app/public/png/500.png")}}" alt="">
            </div>
            <h2 class="page-not-found">Server Error</h2>
            <p style="text-align: center;">{{\App\CPU\translate('We are sorry, server is not responding')}}. <br> {{\App\CPU\translate('Try after sometime')}}.</p>
        </div>
        <div class="col-md-3"></div>
    </div>
@endsection
