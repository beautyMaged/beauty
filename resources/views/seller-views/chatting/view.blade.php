@extends('layouts.back-end.app')
@section('title',\App\CPU\translate('Chat View'))
@push('css_or_js')
    <link href="{{asset('public/assets/back-end')}}/css/select2.min.css" rel="stylesheet"/>
    <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Message view')}}</li>
        </ol>
    </nav>
    <!-- Page Heading -->
    <div class="container">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-black-50">{{\App\CPU\translate('View User Message')}}</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mt-3 ml-4">
                        <div class="row ">
                            <div class="col-md-3 col-lg-3 hidden-xs hidden-sm">
                                <img class="img-circle __inline-21"
                                     src="{{asset('public/assets/front-end')}}/img/contacts/blank.jpg"
                                     alt="User Pic">
                            </div>

                            <div class=" col-md-9 col-lg-9 hidden-xs hidden-sm">
                                <strong class="mr-3">{{$contact->subject}}</strong>
                                @if($contact->seen==1)
                                    <label class="__rounded-10 text-success border p-1">{{\App\CPU\translate('Seen')}}</label>
                                @else
                                    <label class="__rounded-10 text-danger border p-1">{{\App\CPU\translate('Not Seen Yet')}}</label>
                                @endif
                                <br>
                                <table class="table table-user-information">
                                    <tbody>
                                    <tr>
                                        <td>{{\App\CPU\translate('User name')}}:</td>
                                        <td>{{$contact->name}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{\App\CPU\translate('Mobile Number')}}:</td>
                                        <td>{{$contact->mobile_number}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{\App\CPU\translate('Email')}}:</td>
                                        <td>{{$contact->email}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{\App\CPU\translate('Message')}}</td>
                                        <td><p class="__text-16px"> {{$contact->message}}</p></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <form action="{{route('admin.contact.update',$contact->id)}}" method="post">
                            @csrf
                            <div class="form-group" style="display: none">
                                <div class="row">
                                    <div class="col-md-10">
                                        <h4>{{\App\CPU\translate('Feedback')}}</h4>
                                        <textarea class="form-control " name="feedback" id="" rows="5"
                                                  placeholder="{{\App\CPU\translate('Please send a Feedback')}}">{{$contact->feedback}}</textarea>
                                    </div>
                                </div>
                            </div>


                            <div class="card-footer">
                                <button type="submit" class="btn btn-success float-right">
                                    <i class="fa fa-check"></i> {{\App\CPU\translate('Seen')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
